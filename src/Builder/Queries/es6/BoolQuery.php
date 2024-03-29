<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\BoolQueryInterface;
use ElasticORM\Builder\Interfaces\QueryInterface;
use ElasticORM\Builder\BoolQueryTreeBuilder;
use Exception;

class BoolQuery implements QueryInterface, BoolQueryInterface
{
    public BoolQueryTreeBuilder $queryTreeBuilder;

    public function __construct(BoolQueryTreeBuilder $queryTreeBuilder)
    {
        $this->queryTreeBuilder = $queryTreeBuilder;
    }
    public function build(): array
    {
        return $this->queryTreeBuilder->getTree();
    }
    public function addShould(QueryInterface $query): BoolQuery
    {
        $this->queryTreeBuilder->addShould($query->build());
        return $this;
    }
    public function addMust(QueryInterface $query): BoolQuery
    {
        $this->queryTreeBuilder->addMust($query->build());
        return $this;
    }
    public function addFilter(QueryInterface $query)
    {
        try {
            $this->queryTreeBuilder->addFilter($query->build());
        } catch (Exception $exception) {
        }
    }
    public function addMustNot(QueryInterface $query): BoolQuery
    {
        $this->queryTreeBuilder->addMustNot($query->build());
        return $this;
    }

    public function addQuery(QueryInterface $query)
    {
        try {
            $this->queryTreeBuilder->addArrayParam($query->build());
        } catch (Exception $exception) {
        }
    }

    public function setMinimumShouldMatch(int $value): BoolQuery
    {
        $this->queryTreeBuilder->addParam('minimum_should_match', $value);
        return $this;
    }
}
