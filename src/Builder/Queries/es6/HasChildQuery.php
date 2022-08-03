<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;
use ElasticORM\Exception\InvalidValueException;
use ElasticORM\Exception\MissingRequiredFieldException;

class HasChildQuery implements QueryInterface
{
    public const SCORE_MODE_NONE = 'none';
    public const SCORE_MODE_AVG = 'avg';
    public const SCORE_MODE_MAX = 'max';
    public const SCORE_MODE_MIN = 'min';
    public const SCORE_MODE_SUM = 'sum';

    private string $type;
    private QueryInterface $query;

    private ?bool $ignoreUnmapped = null;
    private ?int $maxChildren = null;
    private ?int $minChildren = null;
    private ?string $scoreMode = null;

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
        ]
    ];

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

    public function build(): array
    {
        $this->validate();

        return [
            'has_child' => array_merge(
                [
                    'type' => $this->type,
                    'query' => $this->query->build(),
                ],
                is_null($this->ignoreUnmapped) ? [] : ['ignore_unmapped' => $this->ignoreUnmapped],
                is_null($this->maxChildren) ? [] : ['max_children' => $this->maxChildren],
                is_null($this->minChildren) ? [] : ['min_children' => $this->minChildren],
                is_null($this->scoreMode) ? [] : ['score_mode' => $this->scoreMode],
            )
        ];
    }

    private function validate()
    {
        $queryType = basename(str_replace('\\', '/', self::class));

        foreach (self::REQUIRED_FIELDS as $requiredField) {
            if (empty($this->$requiredField)) {
                throw new MissingRequiredFieldException($queryType, $requiredField);
            }
        }

        foreach (self::VALID_FIELD_VALUES as $field => $validValues) {
            if (!is_null($this->$field) && !in_array($this->$field, $validValues)) {
                throw new InvalidValueException($queryType, $field, $this->$field, implode(', ', $validValues));
            }
        }
    }
}
