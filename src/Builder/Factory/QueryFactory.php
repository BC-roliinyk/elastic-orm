<?php

namespace ElasticORM\Builder\Factory;

use ElasticORM\Builder\Factory\Es6QueryFactory;

class QueryFactory
{
    public const ES_VERSION =  6;
    public static function getQueryObjectFactory()
    {
        if (self::ES_VERSION == 6) {
            return new Es6QueryFactory();
        }
    }
}

