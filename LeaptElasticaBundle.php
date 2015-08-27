<?php

namespace Leapt\ElasticaBundle;

use Leapt\ElasticaBundle\DependencyInjection\Compiler\IndexerCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class LeaptElasticaBundle
 * @package Leapt\ElasticaBundle
 */
class LeaptElasticaBundle extends Bundle
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
