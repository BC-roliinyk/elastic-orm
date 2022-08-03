<?php

namespace ElasticORM\Builder\Interfaces;

interface QueryBuilderInterface
{
    public function setVersion(string $version): void;
    public function setQueryType();
    public function setQuery(QueryInterface $query);
    public function build();
}
