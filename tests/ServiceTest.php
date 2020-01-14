<?php

namespace Leapt\ElasticaBundle\Tests;

use Leapt\ElasticaBundle\Service;
use Leapt\ElasticaBundle\Tests\Mock\BarIndexer;
use Leapt\ElasticaBundle\Tests\Mock\FooIndexer;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    public function testRegisterIndexerWithValidManagedClass(): void
    {
        $mockClient = $this->createMock('Leapt\ElasticaBundle\Client');
        $service = new Service($mockClient, 'plop');

        $service->registerIndexer('foo', new FooIndexer());
        $this->addToAssertionCount(1);
    }

    public function testRegisterIndexerWithInvalidManagedClass(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $mockClient = $this->createMock('Leapt\ElasticaBundle\Client');
        $service = new Service($mockClient, 'plop');

        $service->registerIndexer('bar', new BarIndexer());
    }
}
