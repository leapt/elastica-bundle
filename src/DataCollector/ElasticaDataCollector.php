<?php

namespace Leapt\ElasticaBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;

if (Kernel::VERSION_ID >= 50000) {
    class ElasticaDataCollector extends AbstractCollector
    {
        public function collect(Request $request, Response $response, \Throwable $exception = null): void
        {
            $this->data['nb_queries'] = $this->logger->getNbQueries();
            $this->data['queries'] = $this->logger->getQueries();
        }
    }
} else {
    class ElasticaDataCollector extends AbstractCollector
    {
        public function collect(Request $request, Response $response, \Exception $exception = null): void
        {
            $this->data['nb_queries'] = $this->logger->getNbQueries();
            $this->data['queries'] = $this->logger->getQueries();
        }
    }
}
