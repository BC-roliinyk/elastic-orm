<?php

namespace ElasticORM\Builder\Interfaces;

interface BoolQueryInterface
{
    public function addShould(QueryInterface $query);
    public function addMust(QueryInterface $query);
    public function addMustNot(QueryInterface $query);
    public function addFilter(QueryInterface $query);
}
