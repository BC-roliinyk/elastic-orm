<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use ElasticORM\Builder\Queries\es6\Script;
use ElasticORM\Builder\Queries\es6\ScriptScoreFunction;
use ElasticORM\Builder\Queries\es6\TermQuery;
use ElasticORM\Exception\MissingRequiredFieldException;
use PHPUnit\Framework\TestCase;

class ScriptScoreFunctionTest extends TestCase
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
            'script_score' => [
                'script' => [
                    'source' => '2 * params.a',
                    'params' => [
                        'a' => 3,
                    ],
                ],
            ],
            'weight' => 2.4,
        ];

        $script = (new Script())
            ->setSource(Script::SOURCE_INLINE)
            ->setScript('2 * params.a')
            ->setParams(['a' => 3]);

        $filter = (new TermQuery())
            ->setTerm('foo', 'bar', 1.0);

        $scriptScoreFunction = (new ScriptScoreFunction())
            ->setScript($script)
            ->setWeight(2.4)
            ->setFilter($filter);

        self::assertSame($expected, $scriptScoreFunction->build());
    }

    public function testBuildUsesRequiredValues()
    {
        $expected = [
            'script_score' => [
                'script' => [
                    'source' => '2',
                ],
            ],
        ];

        $script = (new Script())
            ->setSource(Script::SOURCE_INLINE)
            ->setScript('2');

        $scriptScoreFunction = (new ScriptScoreFunction())
            ->setScript($script);

        self::assertSame($expected, $scriptScoreFunction->build());
    }

    public function testBuildWithMissingRequiredFieldsThrowsException()
    {
        self::expectException(MissingRequiredFieldException::class);
        (new ScriptScoreFunction())->build();
    }
}
