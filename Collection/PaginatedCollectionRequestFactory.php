<?php
/*
 * This file licensed under the MIT license.
 *
 * (c) Sylvain Mauduit <swop@swop.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AlphaLabs\FoundationBundle\Collection;

use AlphaLabs\Common\Number\Number;

/**
 * Class PaginatedCollectionRequestFactory
 *
 * @package AlphaLabs\FoundationBundle\Collection
 *
 * @author  Sylvain Mauduit <swop@swop.io>
 */
class PaginatedCollectionRequestFactory
{
    /** @var  int */
    protected $defaultItemPerPageCount;

    /**
     * @param int $defaultItemPerPageCount
     */
    public function __construct($defaultItemPerPageCount = 10)
    {
        $this->defaultItemPerPageCount = $defaultItemPerPageCount;
    }

    /**
     * @param int $page
     * @param int $itemPerPage
     *
     * @return PaginatedCollectionRequestInterface
     */
    public function get($page = null, $itemPerPage = null)
    {
        if (is_null($page)) {
            $page = 1;
        }

        if (is_null($itemPerPage)) {
            $itemPerPage = $this->defaultItemPerPageCount;
        }

        $this->validatePage($page);
        $this->validateItemsPerPage($itemPerPage);

        $paginationInfo = new PaginatedCollectionRequest();

        return $paginationInfo
            ->setPage(intval($page))
            ->setItemsPerPage(intval($itemPerPage));
    }

    /**
     * Checks the page configuration validity
     *
     * @param mixed $page
     *
     * @throws \InvalidArgumentException
     */
    private function validatePage($page)
    {
        if (!Number::isPositiveInteger($page)) {
            throw new \InvalidArgumentException('Page must be a positive integer');
        }
    }

    /**
     * Checks the items per page configuration validity
     *
     * @param mixed $itemPerPage
     *
     * @throws \InvalidArgumentException
     */
    private function validateItemsPerPage($itemPerPage)
    {
        if (!Number::isPositiveInteger($itemPerPage)) {
            throw new \InvalidArgumentException('Items per page must be a positive integer');
        }
    }
}
