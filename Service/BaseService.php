<?php
/*
 * This file licensed under the MIT license.
 *
 * (c) Sylvain Mauduit <swop@swop.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AlphaLabs\FoundationBundle\Service;

use AlphaLabs\Common\Number\Number;
use AlphaLabs\Common\String\String;
use AlphaLabs\FoundationBundle\Collection\PaginatedCollectionRequestFactory;
use AlphaLabs\FoundationBundle\Collection\PaginatedCollectionRequestInterface;
use AlphaLabs\FoundationBundle\Exception\InvalidRequestedPage;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Exception\LessThan1CurrentPageException;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;
use Pagerfanta\Pagerfanta;

/**
 * Class BaseService
 *
 * @package AlphaLabs\FoundationBundle\Service
 *
 * @author  Sylvain Mauduit <swop@swop.io>
 */
abstract class BaseService
{
    /** @const int */
    const IDENTIFIER_TYPE_ID   = 0;
    /** @const int */
    const IDENTIFIER_TYPE_SLUG = 1;

    /** @var  PaginatedCollectionRequestFactory */
    protected $paginatedCollectionRequestFactory;

    /**
     * Get the type of the given identifier.
     * Returns one of [static::IDENTIFIER_TYPE_ID|static::IDENTIFIER_TYPE_SLUG] or null if the identifier is invalid
     *
     * @param mixed $identifier
     *
     * @return int|null
     */
    protected function getIdentifierType($identifier)
    {
        if (Number::isStrictlyPositiveInteger($identifier)) {
            return static::IDENTIFIER_TYPE_ID;
        }

        if (String::isSlug($identifier)) {
            return static::IDENTIFIER_TYPE_SLUG;
        }

        return null;
    }

    /**
     * Sets the paginatedCollectionRequestFactory attribute
     *
     * @param \AlphaLabs\FoundationBundle\Collection\PaginatedCollectionRequestFactory $factory
     *
     * @return $this
     */
    public function setPaginatedCollectionRequestFactory(PaginatedCollectionRequestFactory $factory)
    {
        $this->paginatedCollectionRequestFactory = $factory;

        return $this;
    }

    /**
     * Paginates a subject into Pagination object, which is a view targeted
     * pagination object responsible for the pagination result representation
     *
     * @param \Pagerfanta\Adapter\AdapterInterface $adapter
     * @param PaginatedCollectionRequestInterface  $paginationInfo
     *
     * @throws \LogicException
     * @throws \AlphaLabs\FoundationBundle\Exception\InvalidRequestedPage
     * @return \PagerFanta\PagerFanta
     */
    public function paginate(AdapterInterface $adapter, PaginatedCollectionRequestInterface $paginationInfo = null)
    {
        if (is_null($paginationInfo)) {
            if (is_null($this->paginatedCollectionRequestFactory)) {
                throw new \LogicException(
                    'A PaginatedCollectionRequestFactory must be injected if you want to use pagination '.
                    'and don\t want to handle the pagination info with your own logic.'
                );
            }

            $paginationInfo = $this->paginatedCollectionRequestFactory->get();
        }

        $pager = new Pagerfanta($adapter);

        try {
            $pager
                ->setMaxPerPage($paginationInfo->getItemsPerPage())
                ->setCurrentPage($paginationInfo->getPage());
        } catch (LessThan1CurrentPageException $e) {
            $invalidPageException = new InvalidRequestedPage();

            $invalidPageException
                ->setRequestedPage($paginationInfo->getPage())
                ->setTargetPage(1);

            throw $invalidPageException;
        } catch (OutOfRangeCurrentPageException $e) {
            $invalidPageException = new InvalidRequestedPage();

            $invalidPageException
                ->setRequestedPage($paginationInfo->getPage())
                ->setTargetPage($pager->getNbPages() - 1);

            throw $invalidPageException;
        }

        return $pager;
    }
}
