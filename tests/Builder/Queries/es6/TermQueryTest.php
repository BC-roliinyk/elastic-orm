<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use PHPUnit\Framework\TestCase;
use ElasticORM\Builder\Queries\es6\TermQuery;

class TermQueryTest extends TestCase
{
    public function testBuildUsesProvidedValues()
    {
        $expected = [
            'term' => [
                'foo' => [
                    'value' => 'bar',
                    'boost' => 1.0
                ]
            ]
        ];

        $query = new TermQuery();
        $query->setTerm('foo', 'bar', 1.0);

        self::assertSame($expected, $query->build());
    }

    public function testBuildWithoutBoostUsesShortForm()
    {
        $expected = [
            'term' => [
                'foo' => 'bar'
            ]
        ];

        $query = new TermQuery();
        $query->setTerm('foo', 'bar');

        self::assertSame($expected, $query->build());
    }

    public function testBuildWithoutTermThrowsException()
    {
        self::expectException(\Exception::class);

        (new TermQuery())->build();
    }
}
