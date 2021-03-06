<?php

namespace Leapt\ElasticaBundle;

use Elastica\Client as BaseClient;
use Elastica\Request;
use Leapt\ElasticaBundle\Logger\ElasticaLogger;
use Symfony\Component\Stopwatch\Stopwatch;

class Client extends BaseClient
{
    /**
     * Symfony's debugging Stopwatch.
     *
     * @var Stopwatch|null
     */
    private $stopwatch;

    /**
     * @param string $path
     * @param string $method
     * @param array  $data
     *
     * @return \Elastica\Response
     */
    public function request($path, $method = Request::GET, $data = [], array $query = [])
    {
        if ($this->stopwatch) {
            $this->stopwatch->start('es_request', 'leapt_elastica');
        }
        $start = microtime(true);
        $response = parent::request($path, $method, $data, $query);
        $this->logQuery($path, $method, $data, $query, $start);
        if ($this->stopwatch) {
            $this->stopwatch->stop('es_request');
        }

        return $response;
    }

    /**
     * Sets a stopwatch instance for debugging purposes.
     *
     * @param Stopwatch $stopwatch
     */
    public function setStopwatch(Stopwatch $stopwatch = null)
    {
        $this->stopwatch = $stopwatch;
    }

    /**
     * Log the query if we have an instance of ElasticaLogger.
     *
     * @param string $path
     * @param string $method
     * @param array  $data
     * @param int    $start
     */
    private function logQuery($path, $method, $data, array $query, $start)
    {
        if (!$this->_logger or !$this->_logger instanceof ElasticaLogger) {
            return;
        }
        $time = microtime(true) - $start;
        $connection = $this->getLastRequest()->getConnection();
        $connection_array = [
            'host'      => $connection->getHost(),
            'port'      => $connection->getPort(),
            'transport' => $connection->getTransport(),
            'headers'   => $connection->hasConfig('headers') ? $connection->getConfig('headers') : [],
        ];
        $this->_logger->logQuery($path, $method, $data, $time, $connection_array, $query);
    }
}
