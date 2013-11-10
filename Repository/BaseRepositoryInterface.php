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

use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Interface BaseRepositoryInterface
 *
 * @package AlphaLabs\FoundationBundle\Repository
 *
 * @author  Sylvain Mauduit <swop@swop.io>
 */
interface BaseRepositoryInterface extends ObjectRepository
{
    /**
     * Deletes as entity
     *
     * @param mixed $entity
     */
    public function delete($entity);

    /**
     * Save an entity
     *
     * @param mixed $entity
     */
    public function save($entity);
}
