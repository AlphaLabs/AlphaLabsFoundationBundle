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

/**
 * Class PaginatedCollectionRequest
 *
 * @package AlphaLabs\FoundationBundle\Collection
 *
 * @author  Sylvain Mauduit <swop@swop.io>
 */
class PaginatedCollectionRequest implements PaginatedCollectionRequestInterface
{
    /** @var  int */
    protected $page;
    /** @var  int */
    protected $itemsPerPage;

    /**
     * {@inheritDoc}
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * {@inheritDoc}
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * Sets the itemsPerPage attribute
     *
     * @param int $itemsPerPage
     *
     * @return $this
     */
    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    /**
     * Sets the page attribute
     *
     * @param int $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }
}
