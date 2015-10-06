<?php

namespace Leapt\ElasticaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Leapt\ElasticaBundle\DependencyInjection\Compiler\IndexerCompilerPass;

class LeaptElasticaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new IndexerCompilerPass());
    }

}
