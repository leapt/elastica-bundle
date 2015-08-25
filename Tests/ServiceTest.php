<?php

namespace Jmsche\ElasticaBundle\Tests;

use Jmsche\ElasticaBundle\Service;
use Jmsche\ElasticaBundle\Tests\Mock\BarIndexer;
use Jmsche\ElasticaBundle\Tests\Mock\FooIndexer;

/**
 * Class ServiceTest
 * @package Jmsche\ElasticaBundle\Tests
 */
class ServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterIndexerWithValidManagedClass()
    {
        $mockClient = $this->getMock('Jmsche\ElasticaBundle\Client', [], [], '', false);
        $service = new Service($mockClient, 'plop');

        $service->registerIndexer('foo', new FooIndexer());
        $this->addToAssertionCount(1);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testRegisterIndexerWithInvalidManagedClass()
    {
        $mockClient = $this->getMock('Jmsche\ElasticaBundle\Client', [], [], '', false);
        $service = new Service($mockClient, 'plop');

        $service->registerIndexer('bar', new BarIndexer());
    }
}