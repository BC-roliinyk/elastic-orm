<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\FunctionInterface;
use ElasticORM\Builder\Interfaces\QueryInterface;

class WeightFunction implements FunctionInterface
{
    private const REQUIRED_FIELDS = [
        'weight',
    ];

    private const VALID_FIELD_VALUES = [];

    private float $weight;
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
                'weight' => $this->weight,
            ]
        );
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
