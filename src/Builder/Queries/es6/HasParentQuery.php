<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;
use ElasticORM\Exception\MissingRequiredFieldException;

class HasParentQuery implements QueryInterface
{
    private string $parentType;
    private QueryInterface $query;

    private ?bool $score = null;
    private ?bool $ignoreUnmapped = null;

    private const REQUIRED_FIELDS = [
        'parentType',
        'query',
    ];

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

    public function build(): array
    {
        $this->validate();

        return [
            'has_parent' => array_merge(
                [
                    'parent_type' => $this->parentType,
                    'query' => $this->query->build(),
                ],
                is_null($this->score) ? [] : ['score' => $this->score],
                is_null($this->ignoreUnmapped) ? [] : ['ignore_unmapped' => $this->ignoreUnmapped]
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
    }
}
