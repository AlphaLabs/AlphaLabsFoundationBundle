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

use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestBasedPaginatedCollectionRequestFactory
 *
 * @package AlphaLabs\FoundationBundle\Collection
 *
 * @author  Sylvain Mauduit <swop@swop.io>
 */
class RequestBasedPaginatedCollectionRequestFactory extends PaginatedCollectionRequestFactory
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Sets the request
     *
     * @param Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException If a request isn't affected to the factory
     */
    public function get($page = null, $itemPerPage = null)
    {
        if (null === $this->request) {
            throw new \LogicException('A request must be set in order to use the request-based factory.');
        }

        $currentPaginationInfo = $this->getPaginationInfo($this->request);
        $paginationInfo = array_merge(
            [
                'page'           => 1,
                'items_per_page' => $this->defaultItemPerPageCount
            ],
            $currentPaginationInfo
        );

        return parent::get($paginationInfo['page'], $paginationInfo['items_per_page']);
    }

    /**
     * Gets the pagination information.
     *
     * The returned array must have the following format (filled with the available information if presents)
     *
     * [
     *      'page' => 10,
     *      'items_per_page' => 3
     * ]
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getPaginationInfo(Request $request)
    {
        $info = [];

        if ($request->attributes->has('_pagination_page')) {
            $info['page'] = $request->attributes->get('_pagination_page');
        }

        if ($request->attributes->has('_pagination_items_per_page')) {
            $info['items_per_page'] = $request->attributes->get('_pagination_items_per_page');
        }

        return $info;
    }
}
