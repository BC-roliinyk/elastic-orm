<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use ElasticORM\Builder\Queries\es6\DecayFunction;
use ElasticORM\Builder\Queries\es6\TermQuery;
use ElasticORM\Exception\MissingRequiredFieldException;
use PHPUnit\Framework\TestCase;

class DecayFunctionTest extends TestCase
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
            'linear' => [
                'baz' => [
                    'origin' => '10',
                    'scale' => '100',
                    'offset' => '2',
                    'decay' => 0.5,
                ],
                'multi_value_mode' => 'sum',
            ],
            'weight' => 30.7,
        ];

        $filter = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $decayFunction = (new DecayFunction())
            ->setType(DecayFunction::TYPE_LINEAR)
            ->setField('baz')
            ->setOrigin('10')
            ->setScale('100')
            ->setOffset('2')
            ->setDecay(0.5)
            ->setMultiValueMode(DecayFunction::MULTI_VALUE_MODE_SUM)
            ->setWeight(30.7)
            ->setFilter($filter);

        self::assertSame($expected, $decayFunction->build());
    }

    public function testBuildUsesRequiredValues()
    {
        $expected = [
            'linear' => [
                'baz' => [
                    'scale' => '100',
                ],
            ],
        ];

        $decayFunction = (new DecayFunction())
            ->setType(DecayFunction::TYPE_LINEAR)
            ->setField('baz')
            ->setScale('100');

        self::assertSame($expected, $decayFunction->build());
    }

    public function testBuildWithMissingRequiredFieldsThrowsException()
    {
        self::expectException(MissingRequiredFieldException::class);
        (new DecayFunction())->build();
    }
}
