<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;
use ElasticORM\Builder\QueryTreeBuilder;

class SimpleQueryString implements QueryInterface
{
    public const OR_OPERATOR = 'or';
    public string $query;
    public array $fields;
    public bool $lenient = true;
    public string $defaultOperator = self::OR_OPERATOR;
    public QueryTreeBuilder $queryTreeBuilder;
    public bool $analyzeWildcard = false;


    public function setLenient(bool $lenient): SimpleQueryString
    {
        $this->lenient = $lenient;
        return $this;
    }
    public function setDefaultOperator(string $operator): SimpleQueryString
    {
        $this->defaultOperator = $operator;
        return $this;
    }

    public function setQuery(string $query): SimpleQueryString
    {
        $this->query = $query;
        return $this;
    }
    public function setFields(array $fields): SimpleQueryString
    {
        $this->fields = $fields;
        return $this;
    }
    public function setAnalyzeWildcard(bool $value): SimpleQueryString
    {
        $this->analyzeWildcard = $value;
        return $this;
    }
    public function build(): array
    {
        if ($this->analyzeWildcard === true) {
            return ['simple_query_string' =>
                ['default_operator' => $this->defaultOperator,
                    'analyze_wildcard' => $this->analyzeWildcard,
                    'lenient' => $this->lenient,
                    'query' => $this->query,
                    'fields' => $this->fields
                ]
            ];
        } else {
            return ['simple_query_string' =>
                ['default_operator' => $this->defaultOperator,
                    'lenient' => $this->lenient,
                    'query' => $this->query,
                    'fields' => $this->fields
                ]
            ];
        }
    }
}

