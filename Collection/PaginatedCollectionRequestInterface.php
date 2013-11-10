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
 * interface PaginatedCollectionRequestInterface
 *
 * @package AlphaLabs\FoundationBundle\Collection
 *
 * @author  Sylvain Mauduit <swop@swop.io>
 */
interface PaginatedCollectionRequestInterface
{
    /**
     * Gets the requested page number
     *
     * @return int
     */
    public function getPage();

    /**
     * Gets the requested item per page count
     *
     * @return int
     */
    public function getItemsPerPage();
}
