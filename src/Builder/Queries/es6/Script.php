<?php

namespace ElasticORM\Builder\Queries\es6;

use ElasticORM\Builder\Interfaces\QueryInterface;

class Script implements QueryInterface
{
    public const SOURCE_INLINE = 'source';
    public const SOURCE_STORED = 'id';
    public const SOURCE_FILE = 'file';

    public const LANGUAGE_PAINLESS = 'painless';
    public const LANGUAGE_GROOVY = 'groovy';
    public const LANGUAGE_JAVASCRIPT = 'javascript';
    public const LANGUAGE_PYTHON = 'python';

    private const REQUIRED_FIELDS = [
        'source',
        'script',
    ];

    private const VALID_FIELD_VALUES = [
        'source' => [
            self::SOURCE_INLINE,
            self::SOURCE_STORED,
            self::SOURCE_FILE,
        ],
        'language' => [
            self::LANGUAGE_PAINLESS,
            self::LANGUAGE_GROOVY,
            self::LANGUAGE_JAVASCRIPT,
            self::LANGUAGE_PYTHON,
        ],
    ];

    private string $source;
    private string $script;
    private ?array $params = null;
    private ?string $language = null;

    use ArrayFilterTrait;
    use ValidateTrait;

    public function build(): array
    {
        $this->validate();

        return $this->filter(
            [
                $this->source => $this->script,
                'params' => $this->params,
                'language' => $this->language,
            ]
        );
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function setScript(string $script): self
    {
        $this->script = $script;

        return $this;
    }

    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }
}
