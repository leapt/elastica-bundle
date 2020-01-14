<?php

namespace Leapt\ElasticaBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Leapt\ElasticaBundle\ServiceInterface;

/**
 * This subscriber class listens to Doctrine events and, depending on the registered indexers, automatically
 * triggers index/unindex operations.
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

    public function __construct(ServiceInterface $elastica)
    {
        $this->elastica = $elastica;
        if (null !== $elastica->getIndexers()) {
            foreach ($elastica->getIndexers() as $indexer) {
                $this->managedClasses = array_merge($this->managedClasses, $indexer->getManagedClasses());
            }
        }
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     */
    public function getSubscribedEvents(): array
    {
        return ['postPersist', 'postUpdate', 'preRemove', 'postFlush'];
    }

    public function postPersist(LifecycleEventArgs $ea)
    {
        $this->scheduleForIndexation($ea->getEntity());
    }

    public function postUpdate(LifecycleEventArgs $ea)
    {
        $this->scheduleForIndexation($ea->getEntity());
    }

    public function preRemove(LifecycleEventArgs $ea)
    {
        $this->scheduleForUnindexation($ea->getEntity());
    }

    /**
     * We trigger index/unindex operations on postFlush events.
     */
    public function postFlush(PostFlushEventArgs $ea)
    {
        foreach ($this->scheduledIndexations as $entity) {
            $this->elastica->index($entity);
        }
        $this->scheduledIndexations = [];

        foreach ($this->scheduledUnindexations as $entity) {
            $this->elastica->indexRemove($entity);
        }
        $this->scheduledUnindexations = [];
    }

    /**
     * Schedule the provided entity for an index operation.
     */
    private function scheduleForIndexation($entity)
    {
        $entityHash = spl_object_hash($entity);
        if ($this->isManaged($entity) && !isset($this->scheduledIndexations[$entityHash])) {
            $this->scheduledIndexations[$entityHash] = $entity;
        }
    }

    /**
     * Schedule the provided entity for an unindex operation.
     */
    private function scheduleForUnindexation($entity)
    {
        $entityHash = spl_object_hash($entity);
        if ($this->isManaged($entity) && !isset($this->scheduledUnindexations[$entityHash])) {
            $this->scheduledUnindexations[$entityHash] = $entity;
        }
    }

    /**
     * Determines if the provided entity is managed by the Elastica subscriber.
     */
    private function isManaged($entity): bool
    {
        $managed = false;

        if (\in_array(\get_class($entity), $this->managedClasses, true)) {
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
