<?php

namespace Leapt\ElasticaBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Leapt\ElasticaBundle\ServiceInterface;

/**
 * This subscriber class listens to Doctrine events and, depending on the registered indexers, automatically
 * triggers index/unindex operations
 *
 * @package Leapt\ElasticaBundle\Listener
 */
class IndexSubscriber implements EventSubscriber
{
    /**
     * @var \Leapt\ElasticaBundle\ServiceInterface
     */
    private $elastica;

    /**
     * @var array
     */
    private $managedClasses = [];

    /**
     * @var array
     */
    private $scheduledIndexations = [];

    /**
     * @var array
     */
    private $scheduledUnindexations = [];

    /**
     * @param \Leapt\ElasticaBundle\ServiceInterface $elastica
     */
    public function __construct(ServiceInterface $elastica)
    {
        $this->elastica = $elastica;
        foreach ($elastica->getIndexers() as $indexer) {
            $this->managedClasses = array_merge($this->managedClasses, $indexer->getManagedClasses());
        }
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return ['postPersist', 'postUpdate', 'preRemove', 'postFlush'];
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $ea
     */
    public function postPersist(LifecycleEventArgs $ea)
    {
        $this->scheduleForIndexation($ea->getEntity());
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $ea
     */
    public function postUpdate(LifecycleEventArgs $ea)
    {
        $this->scheduleForIndexation($ea->getEntity());
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $ea
     */
    public function preRemove(LifecycleEventArgs $ea)
    {
        $this->scheduleForUnindexation($ea->getEntity());
    }

    /**
     * We trigger index/unindex operations on postFlush events
     *
     * @param PostFlushEventArgs $ea
     */
    public function postFlush(PostFlushEventArgs $ea)
    {
        foreach($this->scheduledIndexations as $entity)
        {
            $this->elastica->index($entity);
        }
        $this->scheduledIndexations = [];

        foreach($this->scheduledUnindexations as $entity)
        {
            $this->elastica->indexRemove($entity);
        }
        $this->scheduledUnindexations = [];
    }

    /**
     * Schedule the provided entity for an index operation
     *
     * @param $entity
     */
    private function scheduleForIndexation($entity)
    {
        $entityHash = spl_object_hash($entity);
        if ($this->isManaged($entity) && !isset($this->scheduledIndexations[$entityHash])) {
            $this->scheduledIndexations[$entityHash] = $entity;
        }
    }

    /**
     * Schedule the provided entity for an unindex operation
     *
     * @param $entity
     */
    private function scheduleForUnindexation($entity)
    {
        $entityHash = spl_object_hash($entity);
        if ($this->isManaged($entity) && !isset($this->scheduledUnindexations[$entityHash])) {
            $this->scheduledUnindexations[$entityHash] = $entity;
        }
    }

    /**
     * Determines if the provided entity is managed by the Elastica subscriber
     *
     * @param object $entity
     * @return bool
     */
    private function isManaged($entity)
    {
        $managed = false;

        if(in_array(get_class($entity), $this->managedClasses)) {
            $managed = true;
        }

        if (!$managed) {
            // if the entity is a Proxy
            foreach ($this->managedClasses as $class) {
                if ($entity instanceof $class) {
                    $managed = true;
                }
            }
        }

        return $managed;
    }
}
