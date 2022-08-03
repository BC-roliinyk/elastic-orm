<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;

class PrefixQuery implements QueryInterface
{
    private string $fieldName;
    private string $fieldValue;
    private float $boost;
    private ?array $rawPrefixArray;

    public function setPrefix(string $fieldName, string $fieldValue, float $boost): PrefixQuery
    {
        $this->fieldName  = $fieldName;
        $this->fieldValue = $fieldValue;
        $this->boost = $boost;
        return $this;
    }

    public function setRawPrefix (array $prefix): PrefixQuery
    {

        return $this;
    }
    public function build(): array
    {
        return [
            'prefix' => [
                $this->fieldName => [
                    'value' => $this->fieldValue,
                    'boost' => $this->boost
                ]
            ]
        ];
    }
}
