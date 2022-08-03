<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use PHPUnit\Framework\TestCase;
use ElasticORM\Builder\Queries\es6\RangeQuery;

class RangeQueryTest extends TestCase
{
    public function rangeProvider()
    {
        return [
            'gte' => ['foo', ['gte' => 1], ['range' => ['foo' => ['gte' => 1]]]],
            'lte' => ['foo', ['lte' => 1], ['range' => ['foo' => ['lte' => 1]]]],
        ];
    }

    /** @dataProvider rangeProvider */
    public function testBuildUsesProvidedValues($field, $value, $expected)
    {
        $query = new RangeQuery();
        $query->setRange($field, $value);

        self::assertSame($expected, $query->build());
    }

    public function testSetRangeThrowsExceptionWithUnknownOperator()
    {
        self::expectException(\Exception::class);

        $query = new RangeQuery();
        $query->setRange('foo', ['bar' => 1]);
    }

    public function testSetRangeThrowsExceptionWithNonInteger()
    {
        self::expectException(\Exception::class);

        $query = new RangeQuery();
        $query->setRange('foo', ['gte' => 'bar']);
    }
}
