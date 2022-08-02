<?php

namespace ElasticORM\Builder;

class QueryTreeBuilder
{
    public array $queryTree = [];

    public string $queryType;

    public array $prefixArray = [];

    public function __construct(string $queryType, ?string $entityType)
    {
        if (isset($entityType)) {
            $this->queryType = $queryType;
            $this->queryTree['type'] = $entityType;
            $this->queryTree['query'] = [$queryType => []];
        }
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
}
