<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;

class RangeQuery implements QueryInterface
{
    private string $fieldName;
    private array $fieldValue;
    private array $operatorsArray = ['gte', 'lte']; //todo

    /**
     * @throws \Exception
     */
    public function setRange($fieldName, $fieldValue): RangeQuery
    {

        foreach ($fieldValue as $operator => $value) {
            if (!in_array($operator, $this->operatorsArray)) {
                throw new \Exception(
                    "$operator is not one of " . implode(' ', $this->operatorsArray)
                );
            }
            if (!is_int($value)) {
                throw new \Exception(
                    "$value should be integer array " . implode(' ', $fieldValue)
                );
            }
        }

        $this->fieldName = $fieldName;
        $this->fieldValue = $fieldValue;
        return $this;
    }
    public function build(): array
    {
        return [
            'range' => [
                $this->fieldName => $this->fieldValue
            ]
        ];
    }
}
