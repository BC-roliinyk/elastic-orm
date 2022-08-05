<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use ElasticORM\Builder\Queries\es6\FieldValueFactorFunction;
use ElasticORM\Builder\Queries\es6\Sort;
use ElasticORM\Builder\Queries\es6\TermQuery;
use ElasticORM\Exception\InvalidValueException;
use ElasticORM\Exception\MissingRequiredFieldException;
use PHPUnit\Framework\TestCase;

class SortTest extends TestCase
{
    public function testBuildUsesAllValues()
    {
        $expected = [
            'foo' => [
                'order' => 'asc',
                'mode' => 'min',
            ]
        ];

        $sort = (new Sort())
            ->setField('foo')
            ->setOrder(Sort::ORDER_ASC)
            ->setMode(Sort::MODE_MIN);

        self::assertSame($expected, $sort->build());
    }

    public function testBuildUsesRequiredValues()
    {
        $expected = 'foo';

        $sort = (new Sort())
            ->setField('foo');

        self::assertSame($expected, $sort->build());
    }

    public function testBuildWithMissingRequiredFieldsThrowsException()
    {
        self::expectException(MissingRequiredFieldException::class);
        (new Sort())->build();
    }

    public function testBuildWithInvalidValuesThrowsException()
    {
        $sort = (new Sort())
            ->setField('foo')
            ->setOrder('invalid');

        self::expectException(InvalidValueException::class);
        $sort->build();
    }
}
