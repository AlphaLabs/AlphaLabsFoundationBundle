<?php
/*
 * This file licensed under the MIT license.
 *
 * (c) Sylvain Mauduit <swop@swop.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AlphaLabs\FoundationBundle\Exception;

/**
 * Class InvalidRequestedPage
 *
 * @package AlphaLabs\FoundationBundle\Exception
 *
 * @author  Sylvain Mauduit <swop@swop.io>
 */
class InvalidRequestedPage extends \RuntimeException
{
    /** @var  int */
    protected $targetPage;
    /** @var  int */
    protected $requestedPage;

    /**
     * Sets the requestedPage attribute
     *
     * @param int $requestedPage
     *
     * @return $this
     */
    public function setRequestedPage($requestedPage)
    {
        $this->requestedPage = $requestedPage;

        return $this;
    }

    /**
     * Gets the requestedPage attribute
     *
     * @return int
     */
    public function getRequestedPage()
    {
        return $this->requestedPage;
    }

    /**
     * Sets the targetPage attribute
     *
     * @param int $targetPage
     *
     * @return $this
     */
    public function setTargetPage($targetPage)
    {
        $this->targetPage = $targetPage;

        return $this;
    }

    /**
     * Gets the targetPage attribute
     *
     * @return int
     */
    public function getTargetPage()
    {
        return $this->targetPage;
    }


}
