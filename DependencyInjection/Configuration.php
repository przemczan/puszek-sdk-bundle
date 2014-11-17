<?php

namespace Puszek\PuszekClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('puszek_puszek_client');

        $rootNode
            ->children()
                ->arrayNode('server')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('host')->cannotBeEmpty()->end()
                        ->booleanNode('use_ssl')->defaultFalse()->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('client')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->cannotBeEmpty()->end()
                        ->scalarNode('key')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
