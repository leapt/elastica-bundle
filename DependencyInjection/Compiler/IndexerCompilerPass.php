<?php

namespace Leapt\ElasticaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class IndexerCompilerPass
 * @package Leapt\ElasticaBundle\DependencyInjection\Compiler
 */
class IndexerCompilerPass implements CompilerPassInterface
{
    /**
     * Check for indexer services in configuration
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('leapt_elastica.service')) {
            return;
        }
        $definition = $container->getDefinition('leapt_elastica.service');
        foreach ($container->findTaggedServiceIds('leapt_elastica.indexer') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;
            $definition->addMethodCall('registerIndexer', array($alias, new Reference($serviceId)));
        }
    }
}