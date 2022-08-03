<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use ElasticORM\Builder\Queries\es6\HasParentQuery;
use ElasticORM\Builder\Queries\es6\TermQuery;
use ElasticORM\Exception\MissingRequiredFieldException;
use PHPUnit\Framework\TestCase;

class HasParentQueryTest extends TestCase
{
    public function testBuildUsesAllValues()
    {
        $expected = [
            'has_parent' => [
                'parent_type' => 'baz',
                'query' => [
                    'term' => [
                        'foo' => [
                            'value' => 'bar',
                            'boost' => 1.0,
                        ],
                    ],
                ],
                'score' => true,
                'ignore_unmapped' => false,
            ],
        ];

        $termQuery = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $hasParentQuery = (new HasParentQuery())
            ->setParentType('baz')
            ->setQuery($termQuery)
            ->setScore(true)
            ->setIgnoreUnmapped(false);

        self::assertSame($expected, $hasParentQuery->build());
    }

    public function testBuildUsesRequiredValues()
    {
        $expected = [
            'has_parent' => [
                'parent_type' => 'baz',
                'query' => [
                    'term' => [
                        'foo' => [
                            'value' => 'bar',
                            'boost' => 1.0,
                        ],
                    ],
                ],
            ],
        ];

        $termQuery = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $hasParentQuery = (new HasParentQuery())
            ->setParentType('baz')
            ->setQuery($termQuery);

        self::assertSame($expected, $hasParentQuery->build());
    }

    public function testBuildWithMissingRequiredFieldsThrowsException()
    {
        self::expectException(MissingRequiredFieldException::class);
        (new HasParentQuery())->build();
    }
}
