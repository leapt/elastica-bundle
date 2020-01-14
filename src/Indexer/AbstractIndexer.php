<?php

namespace Leapt\ElasticaBundle\Indexer;

use Doctrine\ORM\EntityManager;
use Elastica\Document;
use Elastica\Exception\NotFoundException;
use Elastica\Type;

abstract class AbstractIndexer implements IndexerInterface
{

    /**
     * @var EntityManager
     */
    protected $em;

    public function supports($entity): bool
    {
        $supports = false;

        if (\in_array(get_class($entity), $this->getManagedClasses(), true)) {
            $supports = true;
        }

        if (!$supports) {
            // if the entity is a Proxy
            foreach ($this->getManagedClasses() as $class) {
                if ($entity instanceof $class) {
                    $supports = true;
                }
            }
        }

        return $supports;
    }

    public function getIndexableEntities($entity): array
    {
        return [$entity];
    }

    public function getDocumentIdentifier($entity)
    {
        return $entity->getId();
    }

    public function addIndex($entity, Type $type): void
    {
        $document = new Document($this->getDocumentIdentifier($entity), $this->map($entity, $type));
        $type->addDocument($document);
    }

    public function removeIndex($entity, Type $type): void
    {
        $this->removeIndexById($this->getDocumentIdentifier($entity), $type);
    }

    public function removeIndexById($id, Type $type): void
    {
        try {
            $type->deleteById($id);
        }
        catch(\InvalidArgumentException $e){}
        catch(NotFoundException $e){}
    }

    public function setEntityManager(EntityManager $em): void
    {
        $this->em = $em;
    }
}
