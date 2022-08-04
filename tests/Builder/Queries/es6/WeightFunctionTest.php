<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use ElasticORM\Builder\Queries\es6\RandomScoreFunction;
use ElasticORM\Builder\Queries\es6\TermQuery;
use ElasticORM\Builder\Queries\es6\WeightFunction;
use ElasticORM\Exception\MissingRequiredFieldException;
use PHPUnit\Framework\TestCase;
use stdClass;

class WeightFunctionTest extends TestCase
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
            'weight' => 3.6,
        ];

        $filter = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $weightFunction = (new WeightFunction())
            ->setWeight(3.6)
            ->setFilter($filter);

        self::assertSame($expected, $weightFunction->build());
    }

    public function testBuildUsesRequiredValues()
    {
        $expected = [
            'weight' => 3.6,
        ];

        $weightFunction = (new WeightFunction())
            ->setWeight(3.6);

        self::assertSame($expected, $weightFunction->build());
    }

    public function testBuildWithMissingRequiredFieldsThrowsException()
    {
        self::expectException(MissingRequiredFieldException::class);
        (new WeightFunction())->build();
    }
}
