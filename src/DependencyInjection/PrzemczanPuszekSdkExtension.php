<?php

namespace Przemczan\PuszekSdkBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PrzemczanPuszekSdkExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->getDefinition('przemczan_puszek_sdk.api_connector')
            ->replaceArgument(0, $config);

        if (!empty($config['api_logger'])) {
            $container->getDefinition('przemczan_puszek_sdk.api_connector')
                ->replaceArgument(2, new Reference($config['api_logger']));
        }

        $container->getDefinition('przemczan_puszek_sdk.utils')
            ->replaceArgument(0, $config);
    }
}
