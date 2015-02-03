<?php

namespace Przemczan\PuszekSdkBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('przemczan_puszek_sdk');

        $rootNode
            ->children()
                ->arrayNode('servers')
                    ->isRequired()
                    ->children()
                        ->arrayNode('api')
                            ->isRequired()
                            ->children()
                                ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('port')->defaultValue('5001')->cannotBeEmpty()->end()
                                ->booleanNode('use_ssl')->defaultFalse()->end()
                            ->end()
                        ->end()
                        ->arrayNode('socket')
                            ->isRequired()
                            ->children()
                                ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('port')->defaultValue('5000')->cannotBeEmpty()->end()
                                ->scalarNode('protocol')->defaultValue('ws')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('client')
                    ->isRequired()
                    ->children()
                        ->scalarNode('name')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('key')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->scalarNode('api_logger')->end()
            ->end();

        return $treeBuilder;
    }
}
