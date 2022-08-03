<?php

namespace ElasticORM\Exception;

use Exception;

class InvalidValueException extends Exception
{
    public function __construct(string $queryType, string $field, string $value, string $validValues)
    {
        $message = "The value '{$value}' is not valid for field '{$field}' for query type '{$queryType}'.";
        $message .= " Valid values are: {$validValues}";
        parent::__construct($message, 422);
    }
}
