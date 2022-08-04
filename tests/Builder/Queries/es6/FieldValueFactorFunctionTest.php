<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use ElasticORM\Builder\Queries\es6\FieldValueFactorFunction;
use ElasticORM\Builder\Queries\es6\TermQuery;
use ElasticORM\Exception\InvalidValueException;
use ElasticORM\Exception\MissingRequiredFieldException;
use PHPUnit\Framework\TestCase;

class FieldValueFactorFunctionTest extends TestCase
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
            'field_value_factor' => [
                'field' => 'baz',
                'factor' => 2.3,
                'modifier' => 'ln',
                'missing' => 1.5,
            ],
            'weight' => 6.1,
        ];

        $filter = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $fieldValueFactorFunction = (new FieldValueFactorFunction())
            ->setField('baz')
            ->setFactor(2.3)
            ->setModifier(FieldValueFactorFunction::FIELD_VALUE_FACTOR_MODIFIER_LN)
            ->setMissing(1.5)
            ->setWeight(6.1)
            ->setFilter($filter);

        self::assertSame($expected, $fieldValueFactorFunction->build());
    }

    public function testBuildUsesRequiredValues()
    {
        $expected = [
            'field_value_factor' => [
                'field' => 'baz',
            ],
        ];

        $fieldValueFactorFunction = (new FieldValueFactorFunction())
            ->setField('baz');

        self::assertSame($expected, $fieldValueFactorFunction->build());
    }

    public function testBuildWithMissingRequiredFieldsThrowsException()
    {
        self::expectException(MissingRequiredFieldException::class);
        (new FieldValueFactorFunction())->build();
    }

    public function testBuildWithInvalidValuesThrowsException()
    {
        $fieldValueFactorFunction = (new FieldValueFactorFunction())
            ->setField('baz')
            ->setModifier('invalid');

        self::expectException(InvalidValueException::class);
        $fieldValueFactorFunction->build();
    }
}
