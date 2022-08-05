<?php

namespace ElasticORM\Builder;

use ElasticORM\Builder\Interfaces\QueryInterface;
use ElasticORM\Builder\Queries\es6\ArrayFilterTrait;
use ElasticORM\Builder\Queries\es6\Rescore;
use ElasticORM\Builder\Queries\es6\Script;
use ElasticORM\Builder\Queries\es6\Sort;
use ElasticORM\Builder\Queries\es6\ValidateTrait;

class Search implements QueryInterface
{
    private const REQUIRED_FIELDS = [];
    private const VALID_FIELD_VALUES = [];

    private ?QueryInterface $query = null;
    private ?int $from = null;
    private ?int $size = null;
    private ?array $sorts = null;
    private ?array $source = null;
    //private $storedFields = null;         // Shouldn't be used
    private ?array $scriptFields = null;
    private ?array $docValueFields = null;
    private ?QueryInterface $postFilter = null;
    //private $highlighting = null;         // Will come back to this
    private ?array $rescores = null;
    //private ?string $searchType = null;   // URL param
    //private $scroll = null;               // URL param
    //private $preference = null;           // URL param
    private ?bool $explain = null;
    private ?bool $version = null;
    private ?array $indicesBoost = null;
    private ?float $minScore = null;
    //private $innerHits = null;            // Will come back to this
    //private $collapse = null;             // Will come back to this
    private ?array $searchAfter = null;


    use ArrayFilterTrait;
    use ValidateTrait;

    public function build(): array
    {
        $this->validate();

        $query = $this->query ? $this->query->build() : null;

        $postFilter = $this->postFilter ? $this->postFilter->build() : null;

        return $this->filter(
            [
                'query' => $query,
                'from' => $this->from,
                'size' => $this->size,
                'sort' => $this->sorts,
                '_source' => $this->source,
                'script_fields' => $this->scriptFields,
                'docvalue_fields' => $this->docValueFields,
                'post_filter' => $postFilter,
                'rescore' => $this->rescores,
                'explain' => $this->explain,
                'version' => $this->version,
                'indices_boost' => $this->indicesBoost,
                'min_score' => $this->minScore,
                'search_after' => $this->searchAfter,
            ]
        );
    }

    public function setQuery(QueryInterface $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function setFrom(int $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function clearSorts(): self
    {
        $this->sorts = null;

        return $this;
    }

    public function addSort(Sort $sort): self
    {
        $this->sorts[] = $sort->build();

        return $this;
    }

    public function setSource(?array $includes = null, ?array $excludes = null): self
    {
        $this->source = $this->filter(
            [
                'includes' => $includes,
                'excludes' => $excludes,
            ]
        );

        return $this;
    }

    public function clearScriptFields(): self
    {
        $this->scriptFields = null;

        return $this;
    }

    public function addScriptField(string $field, Script $script): self
    {
        $this->scriptFields[$field]['script'] = $script->build();

        return $this;
    }

    public function setDocValueFields(array $docValueFields): self
    {
        $this->docValueFields = $docValueFields;

        return $this;
    }

    public function setPostFilter(QueryInterface $postFilter): self
    {
        $this->postFilter = $postFilter;

        return $this;
    }

    public function clearRescores(): self
    {
        $this->rescores = null;

        return $this;
    }

    public function addRescore(Rescore $rescore): self
    {
        $this->rescores[] = $rescore->build();

        return $this;
    }

    public function setExplain(bool $explain): self
    {
        $this->explain = $explain;

        return $this;
    }

    public function setVersion(bool $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function clearIndicesBoost(): self
    {
        $this->indicesBoost = null;

        return $this;
    }

    public function addIndexBoost(string $index, float $boost): self
    {
        $this->indicesBoost[] = [$index => $boost];

        return $this;
    }

    public function setMinScore(float $minScore): self
    {
        $this->minScore = $minScore;

        return $this;
    }

    public function setSearchAfter(array $searchAfter): self
    {
        $this->searchAfter = $searchAfter;

        return $this;
    }
}
