<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use ElasticORM\Builder\Queries\es6\Rescore;
use ElasticORM\Builder\Queries\es6\TermQuery;
use ElasticORM\Exception\InvalidValueException;
use ElasticORM\Exception\MissingRequiredFieldException;
use PHPUnit\Framework\TestCase;

class RescoreTest extends TestCase
{
    public function testBuildUsesAllValues()
    {
        $expected = [
            'window_size' => 100,
            'query' => [
                'rescore_query' => [
                    'term' => [
                        'foo' => [
                            'value' => 'bar',
                            'boost' => 1.0,
                        ],
                    ],
                ],
                'score_mode' => 'max',
                'query_weight' => 0.7,
                'rescore_query_weight' => 1.9,
            ],
        ];

        $query = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $rescore = (new Rescore())
            ->setQuery($query)
            ->setWindowSize(100)
            ->setQueryWeight(0.7)
            ->setRescoreQueryWeight(1.9)
            ->setScoreMode(Rescore::SCORE_MODE_MAX);

        self::assertSame($expected, $rescore->build());
    }

    public function testBuildUsesRequiredValues()
    {
        $expected = [
            'query' => [
                'rescore_query' => [
                    'term' => [
                        'foo' => [
                            'value' => 'bar',
                            'boost' => 1.0,
                        ],
                    ],
                ],
            ],
        ];

        $query = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $rescore = (new Rescore())
            ->setQuery($query);

        self::assertSame($expected, $rescore->build());
    }

    public function testBuildWithMissingRequiredFieldsThrowsException()
    {
        self::expectException(MissingRequiredFieldException::class);
        (new Rescore())->build();
    }

    public function testBuildWithInvalidValuesThrowsException()
    {
        $query = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $rescore = (new Rescore())
            ->setQuery($query)
            ->setScoreMode('invalid');

        self::expectException(InvalidValueException::class);
        $rescore->build();
    }
}
