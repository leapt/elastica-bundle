<?php

namespace Leapt\ElasticaBundle;

use Leapt\ElasticaBundle\DependencyInjection\Compiler\IndexerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LeaptElasticaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new IndexerCompilerPass());
    }
}
