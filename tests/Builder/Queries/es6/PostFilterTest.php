<?php

namespace ElasticORM\Tests\Builder\Queries\es6;

use ElasticORM\Builder\Queries\es6\PostFilterQuery;
use ElasticORM\Builder\Queries\es6\TermQuery;
use PHPUnit\Framework\TestCase;

class PostFilterTest extends TestCase
{
    public function testBuildReturnsSetQueryResults()
    {
        $expected = [
            'post_filter' => [
                [
                    [
                        'term' => [
                            'testName' => [
                                'value' => 'testValue',
                                'boost' => 1.0
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $query = new PostFilterQuery();
        $termQuery = (new TermQuery())->setTerm('testName', 'testValue', 1.0);
        $query->setQuery($termQuery);

        self::assertSame($expected, $query->build());
    }

    public function testExceptionThrowWhenQueryNotSet()
    {
        self::expectException(\Exception::class);
        self::expectExceptionMessage('query and raw property not set');
        $query = new PostFilterQuery();
        $query->build();
    }

    public function testBuildReturnsResultsWithRawPropertySet()
    {
        $expected  = [
            'post_filter' => [
                [
                    'term' => 'testTerm'
                ]
            ]
        ];
        $query = new PostFilterQuery();
        $query->rawProperty = ['term' => 'testTerm'];

        self::assertSame($expected, $query->build());
    }

    public function testValidationReturnsFalse()
    {
        $query = new PostFilterQuery();
        $validationResult = $query->validate();
        self::assertSame($validationResult, false);
    }
}
