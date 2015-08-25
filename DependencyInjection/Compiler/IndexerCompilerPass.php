<?php

namespace Jmsche\ElasticaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class IndexerCompilerPass
 * @package Jmsche\ElasticaBundle\DependencyInjection\Compiler
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
        if (false === $container->hasDefinition('jmsche_elastica.service')) {
            return;
        }
        $definition = $container->getDefinition('jmsche_elastica.service');
        foreach ($container->findTaggedServiceIds('jmsche_elastica.indexer') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;
            $definition->addMethodCall('registerIndexer', array($alias, new Reference($serviceId)));
        }
    }
}