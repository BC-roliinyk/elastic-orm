<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;
use ElasticORM\Builder\QueryTreeBuilder;

class SimpleQueryString implements QueryInterface
{
    public const OR_OPERATOR = 'or';
    public string $query;
    public array $fields;
    public bool $lenient = false;
    public string $defaultOperator = self::OR_OPERATOR;
    public QueryTreeBuilder $queryTreeBuilder;
    public bool $analyzeWildcard = false;


    public function setLenient(bool $lenient)
    {
        $this->lenient = $lenient;
        return $this;
    }
    public function setDefaultOperator(string $operator)
    {
        $this->defaultOperator = $operator;
        return $this;
    }

    public function setQuery(string $query)
    {
        $this->query = $query;
        return $this;
    }
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }
    public function setAnalyzeWildcard(bool $value)
    {
        $this->analyzeWildcard = $value;
    }
    public function build(): array
    {
        if ($this->analyzeWildcard === true) {
            return ['simple_query_string' =>
                ['default_operator' => $this->defaultOperator,
                    'analyze_wildcard' => $this->analyzeWildcard,
                    'lenient' => $this->lenient ?? true,
                    'query' => $this->query,
                    'fields' => $this->fields
                ]
            ];
        } else {
            return ['simple_query_string' =>
                ['default_operator' => $this->defaultOperator,
                    'lenient' => $this->lenient ?? true,
                    'query' => $this->query,
                    'fields' => $this->fields
                ]
            ];
        }
    }
}
