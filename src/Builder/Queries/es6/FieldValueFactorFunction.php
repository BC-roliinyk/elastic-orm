<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\FunctionInterface;
use ElasticORM\Builder\Interfaces\QueryInterface;

class FieldValueFactorFunction implements FunctionInterface
{
    public const FIELD_VALUE_FACTOR_MODIFIER_NONE = 'none';
    public const FIELD_VALUE_FACTOR_MODIFIER_LOG = 'log';
    public const FIELD_VALUE_FACTOR_MODIFIER_LOG1P = 'log1p';
    public const FIELD_VALUE_FACTOR_MODIFIER_LOG2P = 'log2p';
    public const FIELD_VALUE_FACTOR_MODIFIER_LN = 'ln';
    public const FIELD_VALUE_FACTOR_MODIFIER_LN1P = 'ln1p';
    public const FIELD_VALUE_FACTOR_MODIFIER_LN2P = 'ln2p';
    public const FIELD_VALUE_FACTOR_MODIFIER_SQUARE = 'square';
    public const FIELD_VALUE_FACTOR_MODIFIER_SQRT = 'sqrt';
    public const FIELD_VALUE_FACTOR_MODIFIER_RECIPROCAL = 'reciprocal';

    private const REQUIRED_FIELDS = [
        'field',
    ];

    private const VALID_FIELD_VALUES = [
        'modifier' => [
            self::FIELD_VALUE_FACTOR_MODIFIER_NONE,
            self::FIELD_VALUE_FACTOR_MODIFIER_LOG,
            self::FIELD_VALUE_FACTOR_MODIFIER_LOG1P,
            self::FIELD_VALUE_FACTOR_MODIFIER_LOG2P,
            self::FIELD_VALUE_FACTOR_MODIFIER_LN,
            self::FIELD_VALUE_FACTOR_MODIFIER_LN1P,
            self::FIELD_VALUE_FACTOR_MODIFIER_LN2P,
            self::FIELD_VALUE_FACTOR_MODIFIER_SQUARE,
            self::FIELD_VALUE_FACTOR_MODIFIER_SQRT,
            self::FIELD_VALUE_FACTOR_MODIFIER_RECIPROCAL,
        ],
    ];

    private string $field;
    private ?float $factor = null;
    private ?string $modifier = null;
    private ?float $missing = null;
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
                'field_value_factor' => [
                    'field' => $this->field,
                    'factor' => $this->factor,
                    'modifier' => $this->modifier,
                    'missing' => $this->missing,
                ],
                'weight' => $this->weight,
            ]
        );
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function setFactor(float $factor): self
    {
        $this->factor = $factor;

        return $this;
    }

    public function setModifier(string $modifier): self
    {
        $this->modifier = $modifier;

        return $this;
    }

    public function setMissing(float $missing): self
    {
        $this->missing = $missing;

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
