<?php
/*
 * This file licensed under the MIT license.
 *
 * (c) Sylvain Mauduit <swop@swop.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AlphaLabs\FoundationBundle\Entity;

/**
 * Interface BaseEntityInterface
 *
 * @package AlphaLabs\FoundationBundle\Entity
 *
 * @author  Sylvain Mauduit <swop@swop.io>
 */
interface BaseEntityInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();
}
