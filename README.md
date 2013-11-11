# AlphaLabs Foundation Bundle

> A useful base for Symfony2 projects: paginated collection, base service & repositories...

This bundle offers some tools which can be helpful to start on a new Symfony2 project.

For the moment, the bundle gives you the following possibilities:

- **Collection pagination**: Use advantage of paginated content, thanks to the integration of the **PagerFanta** library.
- **Base entity/repository with modification date automatic assignation**: A base entity is provided, and coupled with a base repository which manage a transactional persistence of data and automatic assignation of the creation/update time on the entity. Some exceptions are also provided to easily detect when an error occurred during the persistence/fetch of an entity.
- **Base service**: Handle the pagination manipulation, based on pagination information
- **Pagniation param converter**: If the controller has to deal with pagination, a param converter can be used to retrieve information about the pagination request and inject it directly in the controller method parameter.
- Integrate the **LiipDoctrineCacheBundle** to use cache system in your project

## Installation

Adds the library in your `composer.json` file:

````json
"require": {
    "alphalabs/foundation-bundle": "1.0@dev"
}
````

Don't forget to update your dependencies with `composer update`

Adds the following bundle declaration in your `AppKernel.php` file:

````php
public function registerBundles()
    {
        $bundles = array(
            // ...
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Liip\DoctrineCacheBundle\LiipDoctrineCacheBundle(),
        );
````

## Configuration

You can configure the bundle in your `config.yml` file:

````yaml
alphalabs_foundation:
    pagination:
        items_per_page: 20 # Default number of items per page in paginated collections

# Bundle used to handle automatic insertion of creation/update dates during entity persistence
stof_doctrine_extensions:
    default_locale: en_US
    orm:
        default:
            timestampable: true
````
To configure the caching system, have a look on the **LiipDoctrineCacheBundle** configuration reference.

You can use more Doctrine extensions if you want. In this case, have a look on the **StofDoctrineExtensionBundle** configuration reference.

## Usage

### Base Entity, Repository
#### Entity
In order to use the automatic date injection in your entities, you have to make then extends the base entity:

````php
<?php

namespace MyBundle\Entity;

use AlphaLabs\Foundation\Entity\BaseEntity;

class MyEntity extends BaseEntity
{
}
````
The `ìd`, `createdAt` and `updatedAt` fields and their corresponding getters/setters are now available in the entity.

More over, these fields are already mapped in Doctrine. You doesn't have to declare mapping for these fields.

#### EntityInterface

If you want to use interface to represents your entity, you can make your interface extends the base entity interface which describe the new setters/getters:

````php
<?php

namespace MyBundle\Entity;

use AlphaLabs\Foundation\Entity\BaseEntityInterface;

interface MyEntityInterface extends BaseEntityInterface
{
}
````

#### Repository

A based repository can also be used to have some extra methods which can be useful when managing entities. Simply extends this class in your custom repositories instead of the Doctrine's `EntityRepository` one:

````php
<?php

namespace MyBundle\Repository;

use AlphaLabs\Foundation\Repository\BaseRepository;

class MyRepository extends BaseRepository
{
}
````
This class offers some method to deal with Query/QueryBuilder/NativeQuery creation, the creation of the PagerFanta adapter and expose a `save` and `delete` method to persist and remove your entities.

#### RepositoryInterface

Like the `EntityInterface`, an interface can be used for the repository if you want to use them elsewhere (in your services, for example).

You can make your interface extends the base repository interface which describe the added methods:

````php
<?php

namespace MyBundle\Repository;

use AlphaLabs\Foundation\Repository\BaseRepositoryInterface;

interface MyRepositoryInterface extends BaseRepositoryInterface
{
}
````

### Base Service/Pagination ###

A base service os provided to make easy the entity fetching: fetch an entity by its id or its slug, and manage pagination when fetching multiple entities.

You can extends your custom serviced from BaseService (and same for your interfaces), and use the provided methods to facilitate entity fetching:

````php
<?php

namespace MyBundle\Service;

use AlphaLabs\FoundationBundle\Collection\PaginatedCollectionRequestInterface;
use AlphaLabs\FoundationBundle\Exception\InvalidIdentifierException;
use AlphaLabs\FoundationBundle\Exception\ObjectNotFoundException;
use AlphaLabs\FoundationBundle\Service\BaseService;
use MyBundle\Entity\MyEntityInterface;
use MyBundle\Repository\MyRepositoryInterface;

class MyService extends BaseService implements MyServiceInterface
{
    /** @var MyRepositoryInterface */
    protected $myRepository;

    /**
     * {@inheritDoc}
     */
    public function getAll(PaginatedCollectionRequestInterface $paginationInfo = null)
    {
        return $this->paginate($this->myRepository->findAllAdapter(), $paginationInfo);
    }

    /**
     * {@inheritDoc}
     */
    public function get($identifier)
    {
        switch($this->getIdentifierType($identifier)) {
            case static::IDENTIFIER_TYPE_ID:
                $entity = $this->myRepository->find(intval($identifier));
                break;
            case static::IDENTIFIER_TYPE_SLUG:
                $entity = $this->myRepository->findBySlug($identifier);
                break;
            default:
                throw new InvalidIdentifierException();
        }

        if (is_null($snippet)) {
            throw new ObjectNotFoundException();
        }

        return $entity;
    }
}
````

And the corresponding interface:

````php
<?php

namespace MyBundle\Service;

use AlphaLabs\Foundation\Service\BaseServiceInterface;
use AlphaLabs\FoundationBundle\Collection\PaginatedCollectionRequestInterface;
use Pagerfanta\Pagerfanta;

interface MyServiceInterface extends BaseServiceInterface
{
    /**
     * Gets all my entities (paginated)
     *
     * @param \AlphaLabs\FoundationBundle\Collection\PaginatedCollectionRequestInterface $paginationInfo
     *
     * @return Pagerfanta
     */
    public function getAll(PaginatedCollectionRequestInterface $paginationInfo = null);

    /**
     * Gets my entity by the given identifier (slug or id)
     *
     * @param int|string $identifier
     *
     * @return MyEntity
     */
    public function get($identifier);
}
````

### Pagination Information ###

You can use pagination request in your controllers. It can be simply injected in the controller's method parameters by a ParamConverter.

If your controller deals with some paginated content, just add the argument in the method declaration:

````php
<?php

namespace MyBundle\Controller;

use AlphaLabs\FoundationBundle\Collection\PaginatedCollectionRequestInterface;
use AlphaLabs\FoundationBundle\Exception\InvalidRequestedPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MyController extends Controller
{
    public function listAction(PaginatedCollectionRequestInterface $paginationInfo)
    {
        $myService = $this->get('my-service');

        try {
            $entities = $myService->getAll($paginationInfo);
        } catch (InvalidRequestedPage $e) {
            return $this->redirect($this->generateUrl('myentities_list', ['_pagination_page' => $e->getTargetPage()]));
        }

        return $this->render('MyBundle:list.html.twig', ['entities' => $entities]);
    }
````

In the `$myService->getAll()` method, `$this->paginate()` will be called which automatically throw an exception if the requested page is wrong (to low or to high). The exception object contains the nearest available page (1 if the requested page was too low, and the last page if the requested page was too hight).

The injected `$paginationInfo` is build based on the `_pagination_page` parameters in the request attributes bag. For the ParamConverter to work, you can declare this parameter in your routing file:

````xml
<?xml version="1.0" encoding="UTF-8" ?>

<routes xmlns="http://symfony.com/schema/routing"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/routing http://symfony.com/schema/routing/routing-1.0.xsd">
    <route id="snippet_list" pattern="/{_pagination_page}">
        <default key="_controller">MyBundle:MyController:list</default>
        <default key="_pagination_page">1</default>
        <requirement key="_pagination_page">\d+</requirement>
    </route>
</routes>
````

If you doesn't want to use the URL to indicate the requested page, you can create a listener to detect the page and inject a `_pagination_page` parameter into the request attributes.

A `_pagination_items_per_page` can be used the same way to use an other number of items per page that the default one. It will also be stored in the `PaginatedCollectionRequestInterface` injected by the ParamConverter.

### Caching ###

To use caching system in your project, you can use the LiipDoctrineCacheBundle which is installed by the AlphaLabsFoundationBundle.

For more information about the caching system usage, please have a look at the
**LiipDoctrineCacheBundle** documentation.

## To come

- Transaction management in service-scope level (rollback/flush all operations at the end of the service method) with provided save/delete methods.
- (propose your ideas)

## Credits

- Sylvain Mauduit (@Swop)

## License

MIT
