<?php

namespace AlphaLabs\FoundationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AlphaLabsFoundationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $declarationFiles = ['service.xml', 'collection.xml'];

        foreach ($declarationFiles as $file) {
            $loader->load($file);
        }

        $this->configurePagination($config['pagination'], $container);
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'alphalabs_foundation';
    }

    /**
     * @param array            $config    Pagination configuration
     * @param ContainerBuilder $container Container
     */
    private function configurePagination(array $config, ContainerBuilder $container)
    {
        $container->setParameter('alphalabs.foundation.pagination.default_items_per_page', $config['items_per_page']);
    }
}
