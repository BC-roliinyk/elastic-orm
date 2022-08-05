<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;

class HasParentQuery implements QueryInterface
{
    private const REQUIRED_FIELDS = [
        'parentType',
        'query',
    ];

    private const VALID_FIELD_VALUES = [];

    private string $parentType;
    private QueryInterface $query;
    private ?bool $score = null;
    private ?bool $ignoreUnmapped = null;

    use ArrayFilterTrait;
    use ValidateTrait;

    public function build(): array
    {
        $this->validate();

        return $this->filter(
            [
                'has_parent' => [
                    'parent_type' => $this->parentType,
                    'query' => $this->query->build(),
                    'score' => $this->score,
                    'ignore_unmapped' => $this->ignoreUnmapped,
                ],
            ]
        );
    }

    public function setParentType(string $parentType): self
    {
        $this->parentType = $parentType;

        return $this;
    }

    public function setQuery(QueryInterface $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function setScore(bool $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function setIgnoreUnmapped(bool $ignoreUnmapped): self
    {
        $this->ignoreUnmapped = $ignoreUnmapped;

        return $this;
    }
}
