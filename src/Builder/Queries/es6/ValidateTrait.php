<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Exception\InvalidValueException;
use ElasticORM\Exception\MissingRequiredFieldException;

trait ValidateTrait
{
    private function validate(): void
    {
        $queryType = basename(str_replace('\\', '/', self::class));

        foreach (self::REQUIRED_FIELDS as $requiredField) {
            if (empty($this->$requiredField)) {
                throw new MissingRequiredFieldException($queryType, $requiredField);
            }
        }

        foreach (self::VALID_FIELD_VALUES as $field => $validValues) {
            if (!is_null($this->$field) && !in_array($this->$field, $validValues)) {
                throw new InvalidValueException($queryType, $field, $this->$field, implode(', ', $validValues));
            }
        }
    }
}
