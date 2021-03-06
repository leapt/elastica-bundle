<?php

namespace Leapt\ElasticaBundle;

use Leapt\ElasticaBundle\Indexer\IndexerInterface;

interface ServiceInterface
{
    /**
     * Create indexes as defined in the config.
     */
    public function createIndexes();

    /**
     * Reindex all indexable content.
     */
    public function reindex();

    /**
     * Index the provided entity (or unindex it if its indexer asks us too).
     *
     * @param object $entity
     */
    public function index($entity);

    /**
     * Unindex the provided entity.
     *
     * @param object $entity
     *
     * @return mixed
     */
    public function indexRemove($entity);

    /**
     * Perform a simple search on the given index and types.
     *
     * @param string       $query
     * @param string|array $index
     * @param array        $types
     *
     * @return \Elastica\ResultSet
     */
    public function search($query, $index, $types = null);

    /**
     * Register an index.
     *
     * @param string                   $alias
     * @param Indexer\IndexerInterface $indexer
     */
    public function registerIndexer($alias, IndexerInterface $indexer);

    /**
     * Return all indexers.
     *
     * @return array
     */
    public function getIndexers();

    /**
     * Set indexes.
     */
    public function setIndexes(array $indexes);
}
