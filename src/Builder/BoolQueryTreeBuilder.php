<?php

namespace ElasticORM\Builder;

class BoolQueryTreeBuilder
{
    public array $queryTree = [];

    public function __construct()
    {
        $this->queryTree['bool'] = [];
    }

    public function addParam($paramName, $paramValue)
    {
        $this->queryTree['bool'][$paramName] = $paramValue;
    }

    public function addArrayParam($array)
    {
        $this->queryTree['bool'][] = $array;
    }

    public function getTree(): array
    {
        return $this->queryTree;
    }

    public function addShould(array $array)
    {
        $this->queryTree['bool']['should'][] = $array;
    }

    public function addMust(array $array)
    {
        $this->queryTree['bool']['must'][] = $array;
    }

    public function addMustNot(array $array)
    {
        $this->queryTree['bool']['must_not'][] = $array;
    }

    public function addFilter(array $array)
    {
        $this->queryTree['bool']['filter'][] = $array;
    }
}
