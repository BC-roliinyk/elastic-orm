<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;

class RangeQuery implements QueryInterface
{
    private string $fieldName;
    private array $fieldValue;
    private array $modificatorsArray = ['gte', 'lte']; //todo

    /**
     * @throws \Exception
     */
    public function setRange($fieldName, $fieldValue): RangeQuery
    {

        foreach ($fieldValue as $modificator => $value) {
            if (!in_array($modificator, $this->modificatorsArray)) {
                throw new \Exception(
                    "$modificator is not one of " . implode(' ', $this->modificatorsArray)
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
