<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use ElasticORM\Builder\Queries\es6\Script;
use ElasticORM\Exception\InvalidValueException;
use ElasticORM\Exception\MissingRequiredFieldException;
use PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase
{
    public function testBuildUsesAllValues()
    {
        $expected = [
            'source' => '2 * params.a',
            'params' => [
                'a' => 3,
            ],
            'language' => 'painless',
        ];

        $script = (new Script())
            ->setSource(Script::SOURCE_INLINE)
            ->setScript('2 * params.a')
            ->setParams(['a' => 3])
            ->setLanguage(Script::LANGUAGE_PAINLESS);

        self::assertSame($expected, $script->build());
    }

    public function testBuildUsesRequiredValues()
    {
        $expected = [
            'source' => '2',
        ];

        $script = (new Script())
            ->setSource(Script::SOURCE_INLINE)
            ->setScript('2');

        self::assertSame($expected, $script->build());
    }

    public function testBuildWithMissingRequiredFieldsThrowsException()
    {
        self::expectException(MissingRequiredFieldException::class);
        (new Script())->build();
    }

    public function testBuildWithInvalidValuesThrowsException()
    {
        $script = (new Script())
            ->setSource('invalid')
            ->setScript('2');

        self::expectException(InvalidValueException::class);
        $script->build();
    }
}
