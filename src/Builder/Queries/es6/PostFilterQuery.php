<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;
use ElasticORM\Builder\QueryTreeBuilder;
use Exception;

class PostFilterQuery implements QueryInterface
{
    public QueryTreeBuilder $queryTreeBuilder;
    public ?QueryInterface $query;
    public $rawProperty = [];


    public function setQuery(QueryInterface $query)
    {
        try {
            $this->query = $query;
        } catch (Exception $exception) {
        }
    }

    public function setRawQuery(array $array) {
        $this->rawPropety = $array;
    }

    public function validate() {
        return (isset($this->query) || !empty($this->rawProperty));
    }

    public function build(): array
    {
        if ($this->validate()) {
            return [
                'post_filter' => [
                    $this->query ? [$this->query->build()] : $this->rawProperty
                ]
            ];
        }
        throw new \Exception('query and raw property not set');
    }

}
