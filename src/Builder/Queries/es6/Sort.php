<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;

class Sort implements QueryInterface
{
    public const ORDER_ASC = 'asc';
    public const ORDER_DESC = 'desc';

    public const MODE_MIN = 'min';
    public const MODE_MAX = 'max';
    public const MODE_SUM = 'sum';
    public const MODE_AVG = 'avg';
    public const MODE_MEDIAN = 'median';

    private const REQUIRED_FIELDS = [
        'field',
    ];

    private const VALID_FIELD_VALUES = [
        'order' => [
            self::ORDER_ASC,
            self::ORDER_DESC,
        ],
        'mode' => [
            self::MODE_MIN,
            self::MODE_MAX,
            self::MODE_SUM,
            self::MODE_AVG,
            self::MODE_MEDIAN,
        ],
    ];

    private string $field;
    private ?string $order = null;
    private ?string $mode = null;

    use ArrayFilterTrait;
    use ValidateTrait;

    public function build()
    {
        $this->validate();

        if (is_null($this->order) && is_null($this->mode)) {
            return $this->field;
        } else {
            return $this->filter(
                [
                    $this->field => [
                        'order' => $this->order,
                        'mode' => $this->mode
                    ],
                ]
            );
        }
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function setOrder(string $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }
}
