<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\FunctionInterface;
use ElasticORM\Builder\Interfaces\QueryInterface;

class DecayFunction implements FunctionInterface
{
    public const TYPE_GAUSS = 'gauss';
    public const TYPE_LINEAR = 'linear';
    public const TYPE_EXP = 'exp';

    public const MULTI_VALUE_MODE_MIN = 'min';
    public const MULTI_VALUE_MODE_MAX = 'max';
    public const MULTI_VALUE_MODE_AVG = 'avg';
    public const MULTI_VALUE_MODE_SUM = 'sum';

    private const REQUIRED_FIELDS = [
        'type',
        'field',
        'scale',
    ];

    private const VALID_FIELD_VALUES = [
        'type' => [
            self::TYPE_GAUSS,
            self::TYPE_LINEAR,
            self::TYPE_EXP,
        ],
        'multiValueMode' => [
            self::MULTI_VALUE_MODE_MIN,
            self::MULTI_VALUE_MODE_MAX,
            self::MULTI_VALUE_MODE_AVG,
            self::MULTI_VALUE_MODE_SUM,
        ],
    ];

    private string $type;
    private string $field;
    private string $scale;
    private ?string $origin = null;
    private ?string $offset = null;
    private ?float $decay = null;
    private ?string $multiValueMode = null;
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
                $this->type => [
                    $this->field => [
                        'origin' => $this->origin,
                        'scale' => $this->scale,
                        'offset' => $this->offset,
                        'decay' => $this->decay,
                    ],
                    'multi_value_mode' => $this->multiValueMode,
                ],
                'weight' => $this->weight,
            ]
        );
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function setScale(string $scale): self
    {
        $this->scale = $scale;

        return $this;
    }

    public function setOrigin(string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function setOffset(string $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function setDecay(float $decay): self
    {
        $this->decay = $decay;

        return $this;
    }

    public function setMultiValueMode(string $multiValueMode): self
    {
        $this->multiValueMode = $multiValueMode;

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
