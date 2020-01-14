<?php

namespace Leapt\ElasticaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('leapt_elastica');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('config')
                    ->useAttributeAsKey('key')
                    ->prototype('variable')
                    ->treatNullLike([])
                ->end()->end()
                ->scalarNode('namespace')->isRequired()->end()
                ->arrayNode('indexes')
                    ->useAttributeAsKey('key')
                    ->prototype('variable')
                    ->treatNullLike([])
                ->end()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
