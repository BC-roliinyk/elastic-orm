<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;
use ElasticORM\Builder\QueryTreeBuilder;

class PostFilterQuery implements QueryInterface
{
    public QueryTreeBuilder $queryTreeBuilder;
    private string $fieldName;
    private $fieldValue;

    public function __construct()
    {
        $this->queryTreeBuilder = new QueryTreeBuilder('post_filter', false, null);
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
        return ['post_filter' => [
            $this->fieldName => $this->fieldValue
        ]];
    }

    public function setFieldName(string $field) {
        $this->fieldName = $field;
    }

    public function setFieldValue($value) {
        $this->fieldValue = $value;
    }
}
