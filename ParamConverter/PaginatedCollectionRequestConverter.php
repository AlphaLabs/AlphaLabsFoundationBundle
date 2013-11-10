<?php
/*
 * This file licensed under the MIT license.
 *
 * (c) Sylvain Mauduit <swop@swop.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AlphaLabs\FoundationBundle\ParamConverter;

use AlphaLabs\FoundationBundle\Collection\RequestBasedPaginatedCollectionRequestFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PaginatedCollectionRequestConverter
 *
 * @package AlphaLabs\FoundationBundle\ParamConverter
 *
 * @author  Sylvain Mauduit <swop@swop.io>
 */
class PaginatedCollectionRequestConverter implements ParamConverterInterface
{
    /** @const string */
    const PAGINATED_COLLECTION_REQUEST_CLASS
        = "AlphaLabs\\FoundationBundle\\Collection\\PaginatedCollectionRequestInterface";

    /** @var  RequestBasedPaginatedCollectionRequestFactory */
    protected $requestBasedPaginatedCollectionRequestFactory;

    /**
     * Sets the requestBasedPaginatedCollectionRequestFactory attribute
     *
     * @param RequestBasedPaginatedCollectionRequestFactory $requestBasedPaginatedCollectionRequestFactory
     *
     * @return $this
     */
    public function setRequestBasedPaginatedCollectionRequestFactory(
        RequestBasedPaginatedCollectionRequestFactory $requestBasedPaginatedCollectionRequestFactory
    ) {
        $this->requestBasedPaginatedCollectionRequestFactory = $requestBasedPaginatedCollectionRequestFactory;

        return $this;
    }

    /**
     * Stores the object in the request.
     *
     * @param Request                $request       The request
     * @param ConfigurationInterface $configuration Contains the name, class and options of the object
     *
     * @return boolean True if the object has been successfully set, else false
     */
    function apply(Request $request, ConfigurationInterface $configuration)
    {
        $param = $configuration->getName();

        $paginatedCollectionRequest = $this->requestBasedPaginatedCollectionRequestFactory->get();
        $request->attributes->set($param, $paginatedCollectionRequest);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    function supports(ConfigurationInterface $configuration)
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        return static::PAGINATED_COLLECTION_REQUEST_CLASS === $configuration->getClass();
    }
}
