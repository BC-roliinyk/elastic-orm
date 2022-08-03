<?php

namespace ElasticORM\Exception;

use Exception;

class MissingRequiredFieldException extends Exception
{
    public function __construct(string $queryType, string $field)
    {
        $message = "The field '{$field}' is required for query type '{$queryType}'.";
        parent::__construct($message, 422);
    }
}