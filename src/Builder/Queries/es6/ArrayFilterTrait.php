<?php

namespace ElasticORM\Builder\Queries\es6;

trait ArrayFilterTrait
{
    private function filter(array $array): array
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = $this->filter($value);
            }
        }

        return array_filter($array, fn($value) => !is_null($value));
    }
}
