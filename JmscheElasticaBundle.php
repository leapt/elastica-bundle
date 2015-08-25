<?php

namespace Jmsche\ElasticaBundle;

use Jmsche\ElasticaBundle\DependencyInjection\Compiler\IndexerCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class JmscheElasticaBundle
 * @package Jmsche\ElasticaBundle
 */
class JmscheElasticaBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new IndexerCompilerPass());
    }
}
