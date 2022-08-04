<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;
use ElasticORM\Builder\QueryTreeBuilder;

class PostFilterQuery implements QueryInterface
{
    public QueryTreeBuilder $queryTreeBuilder;

    public function __construct()
    {
        $this->queryTreeBuilder = new QueryTreeBuilder('post_filter',null);
    }

    public function addQuery(QueryInterface $query)
    {
        try {
            $this->queryTreeBuilder->addArrayParam($query->build());
        } catch (Exception $exception) {
        }
    }

    public function build(): array
    {
        return $this->queryTreeBuilder->getTree();
    }

}
