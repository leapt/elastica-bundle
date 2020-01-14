<?php

namespace Leapt\ElasticaBundle;

use Leapt\ElasticaBundle\DependencyInjection\Compiler\IndexerCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LeaptElasticaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new IndexerCompilerPass());
    }
}
