<?php

namespace Leapt\ElasticaBundle\DataCollector;

use Leapt\ElasticaBundle\Logger\ElasticaLogger;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

abstract class AbstractCollector extends DataCollector
{
    /**
     * @var ElasticaLogger
     */
    protected $logger;

    public function __construct(ElasticaLogger $logger)
    {
        $this->logger = $logger;
    }

    public function getQueryCount(): int
    {
        return $this->data['nb_queries'];
    }

    public function getQueries(): array
    {
        return $this->data['queries'];
    }

    public function getTime(): int
    {
        $time = 0;
        foreach ($this->data['queries'] as $query) {
            $time += $query['executionMS'];
        }

        return $time;
    }

    public function reset(): void
    {
        $this->data = [
            'nb_queries' => 0,
            'queries'    => [],
        ];
    }

    public function getName(): string
    {
        return 'elastica';
    }
}
