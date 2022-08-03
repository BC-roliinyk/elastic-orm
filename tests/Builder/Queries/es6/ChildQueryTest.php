<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use PHPUnit\Framework\TestCase;
use ElasticORM\Builder\Queries\es6\ChildQuery;
use ElasticORM\Builder\Interfaces\QueryInterface;

class ChildQueryTest extends TestCase
{
    public function testBuildUsesProvidedValues()
    {
        $expected = [
            'has_child' => [
                'type' => 'baz',
                'score_mode' => 'quux',
                ['foo' => 'bar']
            ]
        ];

        $query = new ChildQuery();

        $child = self::createMock(QueryInterface::class);
        $child->expects(self::once())->method('build')->willReturn(['foo' => 'bar']);

        $query->setQuery($child)
            ->setType('baz')
            ->setScoreMode('quux');

        self::assertSame($expected, $query->build());
    }
}
