<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use ElasticORM\Builder\Queries\es6\FunctionScoreQuery;
use ElasticORM\Builder\Queries\es6\RandomScoreFunction;
use ElasticORM\Builder\Queries\es6\TermQuery;
use ElasticORM\Builder\Queries\es6\WeightFunction;
use ElasticORM\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class FunctionScoreQueryTest extends TestCase
{
    public function testBuildUsesAllValues()
    {
        $expected = [
            'function_score' => [
                'query' => [
                    'term' => [
                        'foo' => [
                            'value' => 'bar',
                            'boost' => 1.0,
                        ],
                    ],
                ],
                'boost' => 5.1,
                'max_boost' => 42.3,
                'score_mode' => 'max',
                'boost_mode' => 'multiply',
                'min_score' => 42.1,
                'functions' => [
                    [
                        'filter' => [
                            'term' => [
                                'baz' => [
                                    'value' => 'qux',
                                    'boost' => 1.0,
                                ],
                            ],
                        ],
                        'weight' => 3.6,
                    ],
                    [
                        'filter' => [
                            'term' => [
                                'baz' => [
                                    'value' => 'qux',
                                    'boost' => 1.0,
                                ],
                            ],
                        ],
                        'random_score' => [
                            'seed' => 2,
                        ],
                        'weight' => 1.2,
                    ],
                ],
            ],
        ];

        $query = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $filter = (new TermQuery())
            ->setTerm('baz', 'qux', 1.0);

        $weightFunction = (new WeightFunction())
            ->setWeight(3.6)
            ->setFilter($filter);

        $randomScoreFunction = (new RandomScoreFunction())
            ->setWeight(1.2)
            ->setSeed(2)
            ->setFilter($filter);

        $functionScoreQuery = (new FunctionScoreQuery())
            ->setQuery($query)
            ->setBoost(5.1)
            ->setMaxBoost(42.3)
            ->setScoreMode(FunctionScoreQuery::SCORE_MODE_MAX)
            ->setBoostMode(FunctionScoreQuery::BOOST_MODE_MULTIPLY)
            ->setMinScore(42.1)
            ->addFunction($weightFunction)
            ->addFunction($randomScoreFunction);

        self::assertSame($expected, $functionScoreQuery->build());
    }

    public function testBuildUsesRequiredValues()
    {
        $expected = [
            'function_score' => [
                'functions' => [],
            ],
        ];

        $functionScoreQuery = (new FunctionScoreQuery());

        self::assertSame($expected, $functionScoreQuery->build());
    }

    public function testBuildWithInvalidValuesThrowsException()
    {
        $functionScoreQuery = (new FunctionScoreQuery())
            ->setScoreMode('invalid');

        self::expectException(InvalidValueException::class);
        $functionScoreQuery->build();
    }
}
