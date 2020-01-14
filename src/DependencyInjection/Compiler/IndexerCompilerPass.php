<?php

namespace Leapt\ElasticaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class IndexerCompilerPass.
 */
class IndexerCompilerPass implements CompilerPassInterface
{
    /**
     * Check for indexer services in configuration.
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('Leapt\ElasticaBundle\Service')) {
            return;
        }
        $definition = $container->getDefinition('Leapt\ElasticaBundle\Service');
        foreach ($container->findTaggedServiceIds('leapt_elastica.indexer') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : (method_exists($serviceId, 'getAlias') ? $serviceId::getAlias() : $serviceId);
            $definition->addMethodCall('registerIndexer', [$alias, new Reference($serviceId)]);
        }
    }
}
