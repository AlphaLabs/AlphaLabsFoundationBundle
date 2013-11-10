<?php

namespace AlphaLabs\FoundationBundle;

use AlphaLabs\FoundationBundle\DependencyInjection\AlphaLabsFoundationExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AlphaLabsFoundationBundle
 *
 * @package AlphaLabs\FoundationBundle
 *
 * @author  Sylvain Mauduit <swop@swop.io>
 */
class AlphaLabsFoundationBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function getContainerExtension() {
        if (null === $this->extension) {
            $this->extension = new AlphaLabsFoundationExtension();
        }

        return $this->extension;
    }
}
