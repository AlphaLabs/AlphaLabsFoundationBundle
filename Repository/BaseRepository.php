<?php
/*
 * This file licensed under the MIT license.
 *
 * (c) Sylvain Mauduit <swop@swop.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AlphaLabs\FoundationBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;

/**
 * Class BaseRepository
 *
 * @package AlphaLabs\FoundationBundle\Repository
 *
 * @author  Sylvain Mauduit <swop@swop.io>
 */
abstract class BaseRepository extends EntityRepository implements BaseRepositoryInterface
{
    /**
     * Creates a new Query object.
     *
     * @param string $dql The DQL string.
     * @return \Doctrine\ORM\Query
     */
    protected function createQuery($dql = "")
    {
        $query = new Query($this->_em);

        if (!empty($dql)) {
            $query->setDql($dql);
        }

        return $query;
    }

    /**
     * Creates a native SQL query.
     *
     * @param string $sql
     * @param ResultSetMapping $rsm The ResultSetMapping to use.
     * @return NativeQuery
     */
    protected function createNativeQuery($sql, ResultSetMapping $rsm)
    {
        $query = new NativeQuery($this->_em);

        $query->setSql($sql);
        $query->setResultSetMapping($rsm);

        return $query;
    }

    /**
     * Creates a PagerFanta adapter with the given query
     *
     * @param QueryBuilder $queryBuilder
     *
     * @return \Pagerfanta\Adapter\AdapterInterface
     */
    protected function createPagerAdapter(QueryBuilder $queryBuilder)
    {
        return new DoctrineORMAdapter($queryBuilder);
    }

    /**
     * Deletes as entity
     *
     * @param mixed $entity
     */
    public function delete($entity)
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }

    /**
     * Save an entity
     *
     * @param mixed $entity
     */
    public function save($entity)
    {
        $this->_em->persist($entity);
        $this->_em->flush();
    }
}
