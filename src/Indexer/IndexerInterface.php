<?php

namespace Leapt\ElasticaBundle\Indexer;

use Doctrine\ORM\EntityManager;
use Elastica\Type;

interface IndexerInterface
{
    public const ACTION_REMOVE = 'remove';
    public const ACTION_ADD = 'add';
    public const ACTION_NONE = 'none';

    /**
     * Return an array of classes managed by this indexer
     * Must at least contain the class name of the main entity you wish to index, and may
     * also contain additional classes whose update or deletion should trigger a reindex of
     * the main entity mentioned above.
     *
     * @abstract
     */
    public function getManagedClasses(): array;

    /**
     * Check if the passed entity can be managed by this indexer.
     *
     * @abstract
     *
     * @param object $entity
     */
    public function supports($entity): bool;

    /**
     * Return a mapping array
     * See http://ruflin.github.com/Elastica/ and
     * http://www.elasticsearch.org/guide/reference/mapping/ for more information.
     *
     * @abstract
     *
     * @return mixed
     */
    public function getMappings(): array;

    /**
     * Return of the ACTION_* constants depending on the provided entity
     * Used to determine whether the given entity should be indexed or unindexed.
     *
     * @abstract
     *
     * @param object $entity
     */
    public function getIndexAction($entity, Type $type): string;

    /**
     * Return an iterable of all the entities that need to be reindexed
     * during a rebuild operation.
     *
     * @abstract
     */
    public function getEntitiesToIndex(EntityManager $em, Type $type): iterable;

    /**
     * Get the entities to index provided a given entity
     * In simple cases, this method should simply return an array with the provided entity
     * In some cases, however (depending on the classes returned by getManagedClasses
     * you might want, given an entity of class Foo, index in fact an associated entity of class Bar.
     *
     * @abstract
     *
     * @param object $entity
     */
    public function getIndexableEntities($entity): array;

    /**
     * Determine the elasticsearch document identifier.
     *
     * @abstract
     *
     * @param $entity
     *
     * @return mixed
     */
    public function getDocumentIdentifier($entity);

    /**
     * Return an array of data that can be used to build a Elastica_Document instance.
     *
     * @abstract
     *
     * @param object $entity
     */
    public function map($entity, Type $type): array;

    /**
     * Add (or update) an elasticsearch document for the provided entity.
     *
     * @abstract
     *
     * @param object $entity
     */
    public function addIndex($entity, Type $type): void;

    /**
     * Remove (if existing) the elasticsearch document for the provided entity.
     *
     * @abstract
     *
     * @param object $entity
     */
    public function removeIndex($entity, Type $type): void;

    /**
     * Remove (if existing) the elasticsearch document for the provided id.
     *
     * @abstract
     *
     * @param int $id
     */
    public function removeIndexById($id, Type $type): void;

    /**
     * Store the entity manager.
     */
    public function setEntityManager(EntityManager $em): void;
}
