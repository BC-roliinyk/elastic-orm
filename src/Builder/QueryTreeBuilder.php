<?php

namespace ElasticORM\Builder;

class QueryTreeBuilder
{
    public array $queryTree = [];

    public string $queryType;


    public function __construct(string $queryType, ?string $entityType)
    {
        $this->queryType = $queryType;
        if ($entityType !== null) {
            $this->queryTree['type'] = $entityType;
        }
        $this->queryTree['query'] = [$queryType => []];
    }

    public function addParam($paramName, $paramValue)
    {
        $this->queryTree['query'][$this->queryType][$paramName] = $paramValue;
    }

    public function addArrayParam($array)
    {
        $this->queryTree['query'][$this->queryType][] = $array;
    }

    public function getTree(): array
    {
        return $this->queryTree;
    }
    public function addRootParameter($parameter, $value)
    {
        $this->queryTree['query'][$parameter] = $value;
    }

    public function addShould(array $array)
    {
        $this->queryTree['query'][$this->queryType]['should'][] = $array;
    }

    public function addMust(array $array)
    {
        $this->queryTree['query'][$this->queryType]['must'][] = $array;
    }

    public function addMustNot(array $array)
    {
        $this->queryTree['query'][$this->queryType]['must_not'][] = $array;
    }

    public function addFilter(array $array)
    {
        $this->queryTree['query'][$this->queryType]['filter'][] = $array;
    }
}
