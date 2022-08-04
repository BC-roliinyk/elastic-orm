<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use ElasticORM\Builder\Queries\es6\RandomScoreFunction;
use ElasticORM\Builder\Queries\es6\TermQuery;
use ElasticORM\Exception\MissingRequiredFieldException;
use PHPUnit\Framework\TestCase;
use stdClass;

class RandomScoreFunctionTest extends TestCase
{
    public function testBuildUsesAllValues()
    {
        $expected = [
            'filter' => [
                'term' => [
                    'foo' => [
                        'value' => 'bar',
                        'boost' => 1.0,
                    ],
                ],
            ],
            'random_score' => [
                'seed' => 2,
            ],
            'weight' => 1.2,
        ];

        $filter = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $randomScoreFunction = (new RandomScoreFunction())
            ->setWeight(1.2)
            ->setSeed(2)
            ->setFilter($filter);

        self::assertSame($expected, $randomScoreFunction->build());
    }

    public function testBuildUsesRequiredValues()
    {
        $expected = [
            'random_score' => new stdClass(),
            'weight' => 1.2,
        ];

        $randomScoreFunction = (new RandomScoreFunction())
            ->setWeight(1.2);

        self::assertEquals($expected, $randomScoreFunction->build());
    }

    public function testBuildWithMissingRequiredFieldsThrowsException()
    {
        self::expectException(MissingRequiredFieldException::class);
        (new RandomScoreFunction())->build();
    }
}
