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
        return true;
    }

    public function getMappings(): array
    {
        return [];
    }

    public function getIndexAction($entity, Type $type): string
    {
        return '';
    }

    public function getEntitiesToIndex(EntityManager $em, Type $type): iterable
    {
        return [];
    }

    public function getIndexableEntities($entity): array
    {
        return [];
    }

    public function getDocumentIdentifier($entity)
    {
    }

    public function map($entity, Type $type): array
    {
        return [];
    }

    public function addIndex($entity, Type $type): void
    {
    }

    public function removeIndex($entity, Type $type): void
    {
    }

    public function removeIndexById($id, Type $type): void
    {
    }

    public function setEntityManager(EntityManager $em): void
    {
    }
}
