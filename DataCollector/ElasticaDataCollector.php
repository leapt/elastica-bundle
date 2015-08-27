<?php

namespace Leapt\ElasticaBundle\DataCollector;

use Leapt\ElasticaBundle\Logger\ElasticaLogger;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ElasticaDataCollector
 * @package Leapt\ElasticaBundle\DataCollector
 */
class ElasticaDataCollector extends DataCollector
{
    protected $logger;

    /**
     * @param ElasticaLogger $logger
     */
    public function __construct(ElasticaLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['nb_queries'] = $this->logger->getNbQueries();
        $this->data['queries'] = $this->logger->getQueries();
    }

    /**
     * @return int
     */
    public function getQueryCount()
    {
        return $this->data['nb_queries'];
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->data['queries'];
    }

    /**
     * @return int
     */
    public function getTime()
    {
        $time = 0;
        foreach ($this->data['queries'] as $query) {
            $time += $query['executionMS'];
        }

        return $time;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'elastica';
    }
}
