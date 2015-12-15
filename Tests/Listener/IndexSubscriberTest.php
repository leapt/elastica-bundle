<?php

namespace Leapt\ElasticaBundle\Tests\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Leapt\ElasticaBundle\Listener\IndexSubscriber;
use Leapt\ElasticaBundle\Tests\Listener\Mock\BarEntity;
use Leapt\ElasticaBundle\Tests\Listener\Mock\BazEntity;
use Leapt\ElasticaBundle\Tests\Listener\Mock\FooEntity;
use Leapt\ElasticaBundle\Tests\Listener\Mock\FooEntityProxy;

class IndexSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function testRelevantEntityIsIndexedWhenPersisted()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $foo = new FooEntity();

        $service = $this->getMock('Leapt\ElasticaBundle\Tests\Listener\Mock\Service', array('index'));
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

    public function testLowercaseRelevantEntityIsIndexedWhenPersisted()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $baz  = new BazEntity();

        $service = $this->getMock('Leapt\ElasticaBundle\Tests\Listener\Mock\Service', array('index'));
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

    public function testRelevantEntityIsIndexedOnlyOnceWhenPersisted()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $foo = new FooEntity();

        $service = $this->getMock('Leapt\ElasticaBundle\Tests\Listener\Mock\Service', array('index'));
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

    public function testRelevantEntityProxyIsIndexedWhenPersisted()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $foo = new FooEntityProxy();

        $service = $this->getMock('Leapt\ElasticaBundle\Tests\Listener\Mock\Service', array('index'));
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

    public function testIrrelevantEntityProxyIsNotIndexedWhenPersisted()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $foo = new BarEntity();

        $service = $this->getMock('Leapt\ElasticaBundle\Tests\Listener\Mock\Service', array('index'));
        $service
            ->expects($this->never())
            ->method('index');

        $indexSubscriber = new IndexSubscriber($service);

        $ea = new LifecycleEventArgs($foo, $em);
        $indexSubscriber->postPersist($ea);

        $ea = new PostFlushEventArgs($em);
        $indexSubscriber->postFlush($ea);
    }

    public function testRelevantEntityIsIndexedWhenUpdated()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $foo = new FooEntity();

        $service = $this->getMock('Leapt\ElasticaBundle\Tests\Listener\Mock\Service', array('index'));
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

    public function testRelevantEntityIsUnindexedWhenRemoved()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $foo = new FooEntity();

        $service = $this->getMock('Leapt\ElasticaBundle\Tests\Listener\Mock\Service', array('indexRemove'));
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