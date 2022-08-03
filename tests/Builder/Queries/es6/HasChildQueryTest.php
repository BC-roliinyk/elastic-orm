<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use ElasticORM\Builder\Queries\es6\HasChildQuery;
use ElasticORM\Builder\Queries\es6\TermQuery;
use ElasticORM\Exception\InvalidValueException;
use ElasticORM\Exception\MissingRequiredFieldException;
use PHPUnit\Framework\TestCase;

class HasChildQueryTest extends TestCase
{
    public function testBuildUsesAllValues()
    {
        $expected = [
            'has_child' => [
                'type' => 'baz',
                'query' => [
                    'term' => [
                        'foo' => [
                            'value' => 'bar',
                            'boost' => 1.0,
                        ],
                    ],
                ],
                'ignore_unmapped' => true,
                'max_children' => 3,
                'min_children' => 0,
                'score_mode' => HasChildQuery::SCORE_MODE_MAX,
            ],
        ];

        $termQuery = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $hasChildQuery = (new HasChildQuery())
            ->setType('baz')
            ->setQuery($termQuery)
            ->setIgnoreUnmapped(true)
            ->setMaxChildren(3)
            ->setMinChildren(0)
            ->setScoreMode(HasChildQuery::SCORE_MODE_MAX);

        self::assertSame($expected, $hasChildQuery->build());
    }

    public function testBuildUsesRequiredValues()
    {
        $expected = [
            'has_child' => [
                'type' => 'baz',
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

        $hasChildQuery = (new HasChildQuery())
            ->setType('baz')
            ->setQuery($termQuery);

        self::assertSame($expected, $hasChildQuery->build());
    }

    public function testBuildWithMissingRequiredFieldsThrowsException()
    {
        self::expectException(MissingRequiredFieldException::class);
        (new HasChildQuery())->build();
    }

    public function testBuildWithInvalidValuesThrowsException()
    {
        $termQuery = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $hasChildQuery = (new HasChildQuery())
            ->setType('baz')
            ->setQuery($termQuery)
            ->setScoreMode('invalid');

        self::expectException(InvalidValueException::class);
        $hasChildQuery->build();
    }
}
