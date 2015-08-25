<?php

namespace Jmsche\ElasticaBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jmsche_elastica');
        $rootNode
            ->children()
                ->arrayNode('config')
                    ->useAttributeAsKey('key')
                    ->prototype('variable')
                    ->treatNullLike(array())
                ->end()->end()
                ->scalarNode('namespace')->isRequired()->end()
                ->arrayNode('indexes')
                    ->useAttributeAsKey('key')
                    ->prototype('variable')
                    ->treatNullLike(array())
                ->end()->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
