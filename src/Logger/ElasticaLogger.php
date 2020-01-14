<?php

namespace Leapt\ElasticaBundle\Logger;

use Psr\Log\LoggerInterface;

class ElasticaLogger implements LoggerInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $queries = [];

    /**
     * @var bool
     */
    protected $debug;

    public function __construct(LoggerInterface $logger = null, $debug = false)
    {
        $this->logger = $logger;
        $this->debug = $debug;
    }

    /**
     * Logs a query.
     *
     * @param string $path       Path to call
     * @param string $method     Rest method to use (GET, POST, DELETE, PUT)
     * @param array  $data       Arguments
     * @param float  $time       Execution time
     * @param array  $connection Host, port, transport, and headers of the query
     * @param array  $query      Arguments
     */
    public function logQuery($path, $method, $data, $time, $connection = [], $query = [])
    {
        if ($this->debug) {
            $this->queries[] = [
                'path'        => $path,
                'method'      => $method,
                'data'        => $data,
                'executionMS' => $time,
                'connection'  => $connection,
                'queryString' => $query,
            ];
        }

        if (null !== $this->logger) {
            $message = sprintf("%s (%s) %0.2f ms", $path, $method, $time * 1000);
            $this->logger->info($message, (array) $data);
        }
    }

    /**
     * Returns the number of queries that have been logged.
     */
    public function getNbQueries(): int
    {
        return count($this->queries);
    }

    /**
     * Returns a human-readable array of queries logged.
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    public function emergency($message, array $context = [])
    {
        return $this->logger->emergency($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function alert($message, array $context = [])
    {
        return $this->logger->alert($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function critical($message, array $context = [])
    {
        return $this->logger->critical($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = [])
    {
        return $this->logger->error($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message, array $context = [])
    {
        return $this->logger->warning($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function notice($message, array $context = [])
    {
        return $this->logger->notice($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function info($message, array $context = [])
    {
        return $this->logger->info($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function debug($message, array $context = [])
    {
        return $this->logger->debug($message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = [])
    {
        return $this->logger->log($level, $message, $context);
    }
}