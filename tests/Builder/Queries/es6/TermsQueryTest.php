<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use PHPUnit\Framework\TestCase;
use ElasticORM\Builder\Queries\es6\TermsQuery;

class TermsQueryTest extends TestCase
{
    public function testBuildUsesProvidedValues()
    {
        $expected = [
            'terms' => [
                'foo' => ['bar'],
                'boost' => 1.0
            ]
        ];

        $query = new TermsQuery();
        $query->setTerms('foo', ['bar'], 1.0);

        self::assertSame($expected, $query->build());
    }

    public function testBuildWithoutBoostUsesShortForm()
    {
        $expected = [
            'terms' => [
                'foo' => ['bar']
            ]
        ];

        $query = new TermsQuery();
        $query->setTerms('foo', ['bar']);

        self::assertSame($expected, $query->build());
    }

    public function testBuildWithoutTermThrowsException()
    {
        self::expectException(\Exception::class);

        (new TermsQuery())->build();
    }
}
