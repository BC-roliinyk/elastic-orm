<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;

class ChildQuery implements QueryInterface
{
    public QueryInterface $query;


    public function setQuery(QueryInterface $query)
    {
        $this->query = $query;
        return $this;
    }


    public function build(): array
    {
        return ['has_child' => [
             $this->query->build()
        ]
        ];
    }
}
