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


    public function setLenient($lenient)
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
    public function build(): array
    {
        return ['simple_query_string' =>
            ['default_operator' => $this->defaultOperator,
                'lenient' => $lenient ?? true,
                'query' => $this->query,
                'fields' => $this->fields
            ]
        ];
    }
}
