<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\FunctionInterface;
use ElasticORM\Builder\Interfaces\QueryInterface;

class FunctionScoreQuery implements QueryInterface
{
    public const BOOST_MODE_MULTIPLY = 'multiply';
    public const BOOST_MODE_REPLACE = 'replace';
    public const BOOST_MODE_SUM = 'sum';
    public const BOOST_MODE_AVG = 'avg';
    public const BOOST_MODE_MAX = 'max';
    public const BOOST_MODE_MIN = 'min';

    public const SCORE_MODE_MULTIPLY = 'multiply';
    public const SCORE_MODE_SUM = 'sum';
    public const SCORE_MODE_AVG = 'avg';
    public const SCORE_MODE_FIRST = 'first';
    public const SCORE_MODE_MAX = 'max';
    public const SCORE_MODE_MIN = 'min';

    private const REQUIRED_FIELDS = [];

    private const VALID_FIELD_VALUES = [
        'boostMode' => [
            self::BOOST_MODE_MULTIPLY,
            self::BOOST_MODE_REPLACE,
            self::BOOST_MODE_SUM,
            self::BOOST_MODE_AVG,
            self::BOOST_MODE_MAX,
            self::BOOST_MODE_MIN,
        ],
        'scoreMode' => [
            self::SCORE_MODE_MULTIPLY,
            self::SCORE_MODE_SUM,
            self::SCORE_MODE_AVG,
            self::SCORE_MODE_FIRST,
            self::SCORE_MODE_MAX,
            self::SCORE_MODE_MIN,
        ],
    ];

    private ?QueryInterface $query = null;
    private ?float $boost = null;
    private ?float $maxBoost = null;
    private ?string $scoreMode = null;
    private ?string $boostMode = null;
    private ?float $minScore = null;
    private array $functions = [];

    use ArrayFilterTrait;
    use ValidateTrait;

    public function build(): array
    {
        $this->validate();

        $query = $this->query ? $this->query->build() : null;

        return $this->filter(
            [
                'function_score' => [
                    'query' => $query,
                    'boost' => $this->boost,
                    'max_boost' => $this->maxBoost,
                    'score_mode' => $this->scoreMode,
                    'boost_mode' => $this->boostMode,
                    'min_score' => $this->minScore,
                    'functions' => $this->functions,
                ],
            ]
        );
    }

    public function setQuery(QueryInterface $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function setBoost(float $boost): self
    {
        $this->boost = $boost;

        return $this;
    }

    public function setMaxBoost(float $maxBoost): self
    {
        $this->maxBoost = $maxBoost;

        return $this;
    }

    public function setScoreMode(string $scoreMode): self
    {
        $this->scoreMode = $scoreMode;

        return $this;
    }

    public function setBoostMode(string $boostMode): self
    {
        $this->boostMode = $boostMode;

        return $this;
    }

    public function setMinScore(float $minScore): self
    {
        $this->minScore = $minScore;

        return $this;
    }

    public function clearFunctions(): self
    {
        $this->functions = [];

        return $this;
    }

    public function addFunction(FunctionInterface $function): self
    {
        $this->functions[] = $function->build();

        return $this;
    }
}
