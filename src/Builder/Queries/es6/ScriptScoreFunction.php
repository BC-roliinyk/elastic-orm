<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\FunctionInterface;
use ElasticORM\Builder\Interfaces\QueryInterface;

class ScriptScoreFunction implements FunctionInterface
{
    private const REQUIRED_FIELDS = [
        'source',
    ];

    private const VALID_FIELD_VALUES = [];

    private string $source;
    private ?array $params = null;
    private ?float $weight = null;
    private ?QueryInterface $filter = null;

    use ArrayFilterTrait;
    use ValidateTrait;

    public function build(): array
    {
        $this->validate();

        $filter = $this->filter ? $this->filter->build() : null;

        return $this->filter(
            [
                'filter' => $filter,
                'script_score' => [
                    'script' => [
                        'source' => $this->source,
                        'params' => $this->params,
                    ],
                ],
                'weight' => $this->weight,
            ]
        );
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function setFilter(QueryInterface $filter): self
    {
        $this->filter = $filter;

        return $this;
    }
}
