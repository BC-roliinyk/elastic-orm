<?php

namespace ElasticORM\Builder\Interfaces;

interface QueryFactoryInterface
{
    public function getQueryObject(string $queryType): QueryInterface;
}
