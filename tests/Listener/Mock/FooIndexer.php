<?php

namespace Leapt\ElasticaBundle\Tests\Listener\Mock;

use Doctrine\ORM\EntityManager;
use Elastica\Type;
use Leapt\ElasticaBundle\Indexer\IndexerInterface;

/**
 * Mock indexers for unit tests.
 */
class FooIndexer implements IndexerInterface
{
    public function getManagedClasses(): array
    {
        return [
            'Leapt\ElasticaBundle\Tests\Listener\Mock\FooEntity',
            // Notice the lowercased entity name - we actually want to test this... should work even if the actual
            // class name is BazEntity with a capital E
            'Leapt\ElasticaBundle\Tests\Listener\Mock\Bazentity',
        ];
    }

    public function supports($entity): bool
    {
        // TODO: Implement supports() method.
    }

    public function getMappings(): array
    {
        // TODO: Implement getMappings() method.
    }

    public function getIndexAction($entity, Type $type): string
    {
        // TODO: Implement getIndexAction() method.
    }

    public function getEntitiesToIndex(EntityManager $em, Type $type): array
    {
        // TODO: Implement getEntitiesToIndex() method.
    }

    public function getIndexableEntities($entity): array
    {
        // TODO: Implement getIndexableEntities() method.
    }

    public function getDocumentIdentifier($entity)
    {
        // TODO: Implement getDocumentIdentifier() method.
    }

    public function map($entity, Type $type): array
    {
        // TODO: Implement map() method.
    }

    public function addIndex($entity, Type $type): void
    {
        // TODO: Implement addIndex() method.
    }

    public function removeIndex($entity, Type $type): void
    {
        // TODO: Implement removeIndex() method.
    }

    public function removeIndexById($id, Type $type): void
    {
        // TODO: Implement removeIndexById() method.
    }

    public function setEntityManager(EntityManager $em): void
    {
        // TODO: Implement setEntityManager() method.
    }
}
