<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\FunctionInterface;
use ElasticORM\Builder\Interfaces\QueryInterface;

class ScriptScoreFunction implements FunctionInterface
{
    private const REQUIRED_FIELDS = [
        'script',
    ];

    private const VALID_FIELD_VALUES = [];

    private Script $script;
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
                    'script' => $this->script->build(),
                ],
                'weight' => $this->weight,
            ]
        );
    }

    public function setScript(Script $script): self
    {
        $this->script = $script;

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
