<?php

namespace Leapt\ElasticaBundle\Tests;

use Leapt\ElasticaBundle\Service;
use Leapt\ElasticaBundle\Tests\Mock\BarIndexer;
use Leapt\ElasticaBundle\Tests\Mock\FooIndexer;

/**
 * Class ServiceTest
 * @package Leapt\ElasticaBundle\Tests
 */
class ServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterIndexerWithValidManagedClass()
    {
        $mockClient = $this->getMock('Leapt\ElasticaBundle\Client', [], [], '', false);
        $service = new Service($mockClient, 'plop');

        $service->registerIndexer('foo', new FooIndexer());
        $this->addToAssertionCount(1);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testRegisterIndexerWithInvalidManagedClass()
    {
        $mockClient = $this->getMock('Leapt\ElasticaBundle\Client', [], [], '', false);
        $service = new Service($mockClient, 'plop');

        $service->registerIndexer('bar', new BarIndexer());
    }
}