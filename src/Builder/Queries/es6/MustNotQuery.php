<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;
use ElasticORM\Builder\QueryTreeBuilder;

class MustNotQuery implements QueryInterface
{
    public QueryTreeBuilder $queryTreeBuilder;

    public function __construct()
    {
        $this->queryTreeBuilder = new QueryTreeBuilder('must_not', false,null);
    }

    public function addQuery(QueryInterface $query)
    {
        try {
            $this->queryTreeBuilder->addArrayParam($query->build());
        } catch (Exception $exception) {
        }
    }

    public function addNonQueryArrayParam(QueryInterface $query)
    {
        try {
            $this->queryTreeBuilder->addNonQueryArrayParam($query->build());
        } catch (Exception $exception) {
        }
    }


    public function build(): array
    {
        return $this->queryTreeBuilder->getTree();
    }
}
