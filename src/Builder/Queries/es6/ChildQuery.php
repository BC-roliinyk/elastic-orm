<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;

class ChildQuery implements QueryInterface
{
    public QueryInterface $query;
    public string $type;
    public string $scoreMode;

    public function setQuery(QueryInterface $query): ChildQuery
    {
        $this->query = $query;
        return $this;
    }

    public function setType(string $type): ChildQuery
    {
        $this->type = $type;
        return $this;
    }
    public function setScoreMode(string $scoreMode): ChildQuery
    {
        $this->scoreMode = $scoreMode;
        return $this;
    }

    public function build(): array
    {
        return ['has_child' => [
                'type' => $this->type,
                'score_mode' => $this->scoreMode,
                $this->query->build()
        ]
        ];
    }
}
