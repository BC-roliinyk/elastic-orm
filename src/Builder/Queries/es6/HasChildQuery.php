<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;

class HasChildQuery implements QueryInterface
{
    public const SCORE_MODE_NONE = 'none';
    public const SCORE_MODE_AVG = 'avg';
    public const SCORE_MODE_MAX = 'max';
    public const SCORE_MODE_MIN = 'min';
    public const SCORE_MODE_SUM = 'sum';

    private const REQUIRED_FIELDS = [
        'type',
        'query',
    ];

    private const VALID_FIELD_VALUES = [
        'scoreMode' => [
            self::SCORE_MODE_NONE,
            self::SCORE_MODE_AVG,
            self::SCORE_MODE_MAX,
            self::SCORE_MODE_MIN,
            self::SCORE_MODE_SUM,
        ],
    ];

    private string $type;
    private QueryInterface $query;
    private ?bool $ignoreUnmapped = null;
    private ?int $maxChildren = null;
    private ?int $minChildren = null;
    private ?string $scoreMode = null;

    use ArrayFilterTrait;
    use ValidateTrait;

    public function build(): array
    {
        $this->validate();

        return [
            'has_child' => $this->filter(
                [
                    'type' => $this->type,
                    'query' => $this->query->build(),
                    'ignore_unmapped' => $this->ignoreUnmapped,
                    'max_children' => $this->maxChildren,
                    'min_children' => $this->minChildren,
                    'score_mode' => $this->scoreMode,
                ],
            ),
        ];
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setQuery(QueryInterface $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function setIgnoreUnmapped(bool $ignoreUnmapped): self
    {
        $this->ignoreUnmapped = $ignoreUnmapped;

        return $this;
    }

    public function setMaxChildren(int $maxChildren): self
    {
        $this->maxChildren = $maxChildren;

        return $this;
    }

    public function setMinChildren(int $minChildren): self
    {
        $this->minChildren = $minChildren;

        return $this;
    }

    public function setScoreMode(string $scoreMode): self
    {
        $this->scoreMode = $scoreMode;

        return $this;
    }
}
