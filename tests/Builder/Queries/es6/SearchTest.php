<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use ElasticORM\Builder\Queries\es6\Rescore;
use ElasticORM\Builder\Queries\es6\Script;
use ElasticORM\Builder\Queries\es6\Sort;
use ElasticORM\Builder\Queries\es6\TermQuery;
use ElasticORM\Builder\Search;
use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{
    public function testBuildUsesAllValues()
    {
        $expected = [
            'query' => [
                'term' => [
                    'foo' => [
                        'value' => 'bar',
                        'boost' => 1.0,
                    ],
                ],
            ],
            'from' => 0,
            'size' => 5,
            'sort' => [
                'baz',
            ],
            '_source' => [
                'includes' => [
                    'foo*',
                ],
                'excludes' => [
                    'foobar',
                ],
            ],
            'script_fields' => [
                'qux' => [
                    'script' => [
                        'source' => '2',
                    ],
                ],
            ],
            'docvalue_fields' => [
                'foo',
                'bar',
            ],
            'post_filter' => [
                'term' => [
                    'foo' => [
                        'value' => 'bar',
                        'boost' => 1.0,
                    ],
                ],
            ],
            'rescore' => [
                [
                    'query' => [
                        'rescore_query' => [
                            'term' => [
                                'foo' => [
                                    'value' => 'bar',
                                    'boost' => 1.0,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'explain' => false,
            'version' => true,
            'indices_boost' => [
                [
                    'baz*' => 2.8,
                ],
            ],
            'min_score' => 3.3,
            'search_after' => [
                123,
                'qux',
            ],
        ];

        $query = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $sort = (new Sort())
            ->setField('baz');

        $script = (new Script())
            ->setSource(Script::SOURCE_INLINE)
            ->setScript('2');

        $rescore = (new Rescore())
            ->setQuery($query);

        $search = (new Search())
            ->setQuery($query)
            ->setFrom(0)
            ->setSize(5)
            ->addSort($sort)
            ->setSource(['foo*'], ['foobar'])
            ->addScriptField('qux', $script)
            ->setDocValueFields(['foo', 'bar'])
            ->setPostFilter($query)
            ->addRescore($rescore)
            ->setExplain(false)
            ->setVersion(true)
            ->addIndexBoost('baz*', 2.8)
            ->setMinScore(3.3)
            ->setSearchAfter([123, 'qux']);

        self::assertSame($expected, $search->build());
    }

    public function testBuildUsesRequiredValues()
    {
        $expected = [];

        $search = (new Search());

        self::assertSame($expected, $search->build());
    }
}
