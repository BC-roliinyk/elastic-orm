<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;

class Rescore implements QueryInterface
{
    public const SCORE_MODE_TOTAL = 'total';
    public const SCORE_MODE_MULTIPLY = 'multiply';
    public const SCORE_MODE_AVG = 'avg';
    public const SCORE_MODE_MAX = 'max';
    public const SCORE_MODE_MIN = 'min';

    private const REQUIRED_FIELDS = [
        'query',
    ];

    private const VALID_FIELD_VALUES = [
        'scoreMode' => [
            self::SCORE_MODE_TOTAL,
            self::SCORE_MODE_MULTIPLY,
            self::SCORE_MODE_AVG,
            self::SCORE_MODE_MAX,
            self::SCORE_MODE_MIN,
        ],
    ];

    private QueryInterface $query;
    private ?int $windowSize = null;
    private ?float $queryWeight = null;
    private ?float $rescoreQueryWeight = null;
    private ?string $scoreMode = null;

    use ArrayFilterTrait;
    use ValidateTrait;

    public function build(): array
    {
        $this->validate();

        return $this->filter(
            [
                'window_size' => $this->windowSize,
                'query' => [
                    'rescore_query' => $this->query->build(),
                    'score_mode' => $this->scoreMode,
                    'query_weight' => $this->queryWeight,
                    'rescore_query_weight' => $this->rescoreQueryWeight,
                ]
            ]
        );
    }

    public function setQuery(QueryInterface $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function setWindowSize(int $windowSize): self
    {
        $this->windowSize = $windowSize;

        return $this;
    }

    public function setQueryWeight(float $queryWeight): self
    {
        $this->queryWeight = $queryWeight;

        return $this;
    }

    public function setRescoreQueryWeight(float $rescoreQueryWeight): self
    {
        $this->rescoreQueryWeight = $rescoreQueryWeight;

        return $this;
    }

    public function setScoreMode(string $scoreMode): self
    {
        $this->scoreMode = $scoreMode;

        return $this;
    }
}
