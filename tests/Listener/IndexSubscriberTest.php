<?php

namespace Leapt\ElasticaBundle\Tests\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Leapt\ElasticaBundle\Listener\IndexSubscriber;
use Leapt\ElasticaBundle\Tests\Listener\Mock\BarEntity;
use Leapt\ElasticaBundle\Tests\Listener\Mock\BazEntity;
use Leapt\ElasticaBundle\Tests\Listener\Mock\FooEntity;
use Leapt\ElasticaBundle\Tests\Listener\Mock\FooEntityProxy;
use PHPUnit\Framework\TestCase;

/**
 * Class IndexSubscriberTest
 * @package Leapt\ElasticaBundle\Tests\Listener
 */
class IndexSubscriberTest extends TestCase
{
    public function testRelevantEntityIsIndexedWhenPersisted(): void
    {
        $em = $this->createMock('Doctrine\ORM\EntityManager');
        $foo = new FooEntity();

        $service = $this->getMockBuilder('Leapt\ElasticaBundle\Tests\Listener\Mock\Service')
            ->enableProxyingToOriginalMethods()
            ->getMock();
        $service
            ->expects($this->once())
            ->method('index')
            ->with($this->equalTo($foo));

        $indexSubscriber = new IndexSubscriber($service);

        $ea = new LifecycleEventArgs($foo, $em);
        $indexSubscriber->postPersist($ea);

        $ea = new PostFlushEventArgs($em);
        $indexSubscriber->postFlush($ea);
    }

    public function testLowercaseRelevantEntityIsIndexedWhenPersisted(): void
    {
        $em = $this->createMock('Doctrine\ORM\EntityManager');
        $baz = new BazEntity();

        $service = $this->getMockBuilder('Leapt\ElasticaBundle\Tests\Listener\Mock\Service')
            ->enableProxyingToOriginalMethods()
            ->getMock();
        $service
            ->expects($this->once())
            ->method('index')
            ->with($this->equalTo($baz));

        $indexSubscriber = new IndexSubscriber($service);

        $ea = new LifecycleEventArgs($baz, $em);
        $indexSubscriber->postPersist($ea);

        $ea = new PostFlushEventArgs($em);
        $indexSubscriber->postFlush($ea);
    }

    public function testRelevantEntityIsIndexedOnlyOnceWhenPersisted(): void
    {
        $em = $this->createMock('Doctrine\ORM\EntityManager');
        $foo = new FooEntity();

        $service = $this->getMockBuilder('Leapt\ElasticaBundle\Tests\Listener\Mock\Service')
            ->enableProxyingToOriginalMethods()
            ->getMock();
        $service
            ->expects($this->once())
            ->method('index')
            ->with($this->equalTo($foo));

        $indexSubscriber = new IndexSubscriber($service);

        for($i = 0; $i < 10; ++$i) {
            $ea = new LifecycleEventArgs($foo, $em);
            $indexSubscriber->postPersist($ea);
        }

        $ea = new PostFlushEventArgs($em);
        $indexSubscriber->postFlush($ea);
    }

    public function testRelevantEntityProxyIsIndexedWhenPersisted(): void
    {
        $em = $this->createMock('Doctrine\ORM\EntityManager');
        $foo = new FooEntityProxy();

        $service = $this->getMockBuilder('Leapt\ElasticaBundle\Tests\Listener\Mock\Service')
            ->enableProxyingToOriginalMethods()
            ->getMock();
        $service
            ->expects($this->once())
            ->method('index')
            ->with($this->equalTo($foo));

        $indexSubscriber = new IndexSubscriber($service);

        $ea = new LifecycleEventArgs($foo, $em);
        $indexSubscriber->postPersist($ea);

        $ea = new PostFlushEventArgs($em);
        $indexSubscriber->postFlush($ea);
    }

    public function testIrrelevantEntityProxyIsNotIndexedWhenPersisted(): void
    {
        $em = $this->createMock('Doctrine\ORM\EntityManager');
        $foo = new BarEntity();

        $service = $this->createMock('Leapt\ElasticaBundle\Tests\Listener\Mock\Service');
        $service
            ->expects($this->never())
            ->method('index');

        $indexSubscriber = new IndexSubscriber($service);

        $ea = new LifecycleEventArgs($foo, $em);
        $indexSubscriber->postPersist($ea);

        $ea = new PostFlushEventArgs($em);
        $indexSubscriber->postFlush($ea);
    }

    public function testRelevantEntityIsIndexedWhenUpdated(): void
    {
        $em = $this->createMock('Doctrine\ORM\EntityManager');
        $foo = new FooEntity();

        $service = $this->getMockBuilder('Leapt\ElasticaBundle\Tests\Listener\Mock\Service')
            ->enableProxyingToOriginalMethods()
            ->getMock();
        $service
            ->expects($this->once())
            ->method('index')
            ->with($this->equalTo($foo));

        $indexSubscriber = new IndexSubscriber($service);

        $ea = new LifecycleEventArgs($foo, $em);
        $indexSubscriber->postUpdate($ea);

        $ea = new PostFlushEventArgs($em);
        $indexSubscriber->postFlush($ea);
    }

    public function testRelevantEntityIsUnindexedWhenRemoved(): void
    {
        $em = $this->createMock('Doctrine\ORM\EntityManager');
        $foo = new FooEntity();

        $service = $this->getMockBuilder('Leapt\ElasticaBundle\Tests\Listener\Mock\Service')
            ->enableProxyingToOriginalMethods()
            ->getMock();
        $service
            ->expects($this->once())
            ->method('indexRemove')
            ->with($this->equalTo($foo));

        $indexSubscriber = new IndexSubscriber($service);

        $ea = new LifecycleEventArgs($foo, $em);
        $indexSubscriber->preRemove($ea);

        $ea = new PostFlushEventArgs($em);
        $indexSubscriber->postFlush($ea);
    }
}