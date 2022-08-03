<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;

class TermQuery implements QueryInterface
{
    private string $fieldName;
    private $fieldValue;
    private ?float $boostValue;

    public function setTerm(string $name, $value, ?float $boost = null): TermQuery
    {
        $this->fieldName = $name;
        $this->fieldValue = $value;
        $this->boostValue = $boost;
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function build(): array
    {
        if (empty($this->fieldName)) {
            throw new \Exception('Terms field name has to be set');
        }

        if ($this->boostValue !== null) {
            return [
                'term' => [
                    $this->fieldName => [
                        'value' => $this->fieldValue,
                        'boost' => $this->boostValue
                    ]
                ]
            ];
        } else {
            return [
                'term' => [
                    $this->fieldName => $this->fieldValue
                ]
            ];
        }
    }
}
