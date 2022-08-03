<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use PHPUnit\Framework\TestCase;
use ElasticORM\Builder\Queries\es6\SimpleQueryString;

class SimpleQueryStringTest extends TestCase
{
    public function testBuildUsesProvidedValues()
    {
        $expected = [
            'simple_query_string' => [
                'default_operator' => 'foo',
                'analyze_wildcard' => true,
                'lenient' => true,
                'query' => 'baz',
                'fields' => ['quux']
            ]
        ];

        $query = new SimpleQueryString();
        $query->setDefaultOperator('foo')
            ->setLenient(true)
            ->setQuery('baz')
            ->setFields(['quux'])
            ->setAnalyzeWildcard(true);

        self::assertSame($expected, $query->build());
    }

    public function testBuildUsesDefaultValues()
    {
        $expected = [
            'simple_query_string' => [
                'default_operator' => 'or',
                'lenient' => false,
                'query' => 'foo',
                'fields' => ['bar']
            ]
        ];

        $query = new SimpleQueryString();
        $query->setQuery('foo')
            ->setFields(['bar']);

        self::assertSame($expected, $query->build());
    }
}
