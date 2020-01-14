<?php

namespace Leapt\ElasticaBundle\Paginator;

use Elastica\Query;
use Elastica\ResultSet;
use Leapt\CoreBundle\Paginator\AbstractPaginator;
use Leapt\ElasticaBundle\ServiceInterface;

class ElasticaPaginator extends AbstractPaginator
{
    /**
     * @var Query
     */
    private $elasticaQuery;

    /**
     * @var \Leapt\ElasticaBundle\Service
     */
    private $elastica;

    /**
     * @var ResultSet
     */
    private $resultSet = null;

    /**
     * @var string
     */
    private $index;

    /**
     * @var array
     */
    private $types;

    /**
     * @param Query $query
     * @param $index
     * @param ServiceInterface $elastica
     */
    public function __construct(Query $query, $index, ServiceInterface $elastica = null)
    {
        $this->elasticaQuery = $query;
        $this->index = $index;
        $this->elastica = $elastica;
    }

    /**
     * @param ServiceInterface $elastica
     * @return ElasticaPaginator
     */
    public function setElasticaService(ServiceInterface $elastica)
    {
        $this->elastica = $elastica;

        return $this;
    }

    /**
     * @param int $page
     * @return $this|\Leapt\CoreBundle\Paginator\PaginatorInterface
     * @throws \InvalidArgumentException
     */
    public function setPage($page)
    {
        if($page < 1) {
            throw new \InvalidArgumentException('The page is invalid');
        }
        $this->page = $page;

        return $this;
    }

    /**
     * @param int $limitPerPage
     * @return ElasticaPaginator
     * @throws \InvalidArgumentException
     */
    public function setLimitPerPage($limitPerPage)
    {
        if ($limitPerPage <= 0) {
            throw new \InvalidArgumentException('The limit per page is invalid');
        }
        $this->limitPerPage = $limitPerPage;

        return $this;
    }

    /**
     * @param array $types
     * @return $this
     */
    public function setTypes(array $types)
    {
        $this->types = $types;

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        if (null === $this->resultSet) {
            $this->search();
        }

        return $this->resultSet->getTotalHits();
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        if (null === $this->resultSet) {
            $this->search();
        }

        return $this->resultSet;
    }

    /**
     * @return ResultSet
     */
    public function getResultSet()
    {
        if (null === $this->resultSet) {
            $this->search();
        }

        return $this->resultSet;
    }

    /**
     * Launch the search
     *
     */
    private function search()
    {
        $this->elasticaQuery->setSize($this->limitPerPage);
        $this->elasticaQuery->setFrom($this->getOffset());
        $this->resultSet = $this->elastica->search($this->elasticaQuery, $this->index, $this->types);
    }
}
