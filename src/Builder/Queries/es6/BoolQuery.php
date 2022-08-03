<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\BoolQueryInterface;
use ElasticORM\Builder\Interfaces\QueryInterface;
use ElasticORM\Builder\QueryTreeBuilder;
use Exception;

class BoolQuery implements QueryInterface, BoolQueryInterface
{
    public QueryTreeBuilder $queryTreeBuilder;
    public array $should = ['should'];
    public string $defaultOperator = 'or';
    public function __construct(QueryTreeBuilder $queryTreeBuilder)
    {
        $this->queryTreeBuilder = $queryTreeBuilder;
    }
    public function build(): array
    {
        return $this->queryTreeBuilder->getTree();
    }
    public function addShould(QueryInterface $query)
    {
        $this->addQuery($query);
        return $this;
    }
    public function addMust(QueryInterface $query)
    {
        $this->addQuery($query);
        return $this;
    }
    public function addFilter(QueryInterface $query)
    {
        try {
            $this->queryTreeBuilder->addArrayParam($query->build());
        } catch (Exception $exception) {
        }
    }
    public function addMustNot(QueryInterface $query)
    {
        $this->addQuery($query);
        return $this;
    }
    public function sort($array)
    {
        // TODO: Implement sort() method.
    }
    public function size($value)
    {
        $this->queryTreeBuilder->addRootParameter('size', $value);
        return $this;
    }
    public function from($value)
    {
        $this->queryTreeBuilder->addRootParameter('from', $value);
        return $this;
    }

    public function addQuery(QueryInterface $query)
    {
        try {
            $this->queryTreeBuilder->addArrayParam($query->build());
        } catch (Exception $exception) {
        }
    }

    /*public function addSimpleQuery(
        string $query,
        array $fields,
        ?string $defaultOperator = null,
        ?bool $lenient = null
    ): Es6BoolQuery {
        $array = ['simple_query_string' =>
            ['default_operator' => $defaultOperator ?? $this->defaultOperator,
                'lenient' => $lenient ?? true,
                'query' => $query,
                'fields' => $fields
            ]
        ];
        $this->queryTreeBuilder->addArrayParam($array);
        return $this;
    }*/

    public function setMinimumShouldMatch(int $value): BoolQuery
    {
        $this->queryTreeBuilder->addParam('minimum_should_match', $value);
        return $this;
    }

    public function addPrefix(string $name, string $value, int $boost): BoolQuery
    {
        $inputArray = [
            'prefix' => [
                $name => [
                    'value' => $value,
                    'boost' => $boost
                ]
            ]
        ];
        $this->queryTreeBuilder->addArrayParam($inputArray);

        return $this;
    }
}
