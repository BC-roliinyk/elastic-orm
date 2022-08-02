<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;

class TermsQuery implements QueryInterface
{
    private string $fieldName;
    private array $fieldValue;
    private ?float $boostValue;

    public function setTerms(string $name, array $value, ?float $boost = null): TermsQuery
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
        if ('' === $this->fieldName) {
            throw new \Exception('Terms field name has to be set');
        }

        if ($this->boostValue !== null) {
            return [
                'terms' => [
                    $this->fieldName => [
                        $this->fieldValue,
                        'boost' => $this->boostValue
                    ]
                ]
            ];
        } else {
            return [
                'terms' => [
                    $this->fieldName => $this->fieldValue
                ]
            ];
        }
    }
}
