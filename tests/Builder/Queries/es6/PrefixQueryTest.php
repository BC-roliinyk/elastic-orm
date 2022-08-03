<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use PHPUnit\Framework\TestCase;
use ElasticORM\Builder\Queries\es6\PrefixQuery;

class PrefixQueryTest extends TestCase
{
    public function testBuildUsesProvidedValues()
    {
        $expected = [
            'prefix' => [
                'foo' => [
                    'value' => 'bar',
                    'boost' => 1.0
                ]
            ]
        ];

        $query = new PrefixQuery();
        $query->setPrefix('foo', 'bar', 1.0);

        self::assertSame($expected, $query->build());
    }
}
