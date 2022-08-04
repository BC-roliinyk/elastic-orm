<?php

declare(strict_types=1);

namespace ElasticORM\Tests\Builder\Queries\es6;

use PHPUnit\Framework\TestCase;
use ElasticORM\Builder\Queries\es6\BoolQuery;
use ElasticORM\Builder\QueryTreeBuilder;
use ElasticORM\Builder\Interfaces\QueryInterface;

class BoolQueryTest extends TestCase
{
    public function testAddShouldPassesBuiltValueToQueryTreeBuilder()
    {
        $builder = self::createMock(QueryTreeBuilder::class);
        $builder->expects(self::once())->method('addShould')->with(['foo' => 'bar']);

        $query = new BoolQuery($builder);

        $should = self::createMock(QueryInterface::class);
        $should->expects(self::once())->method('build')->willReturn(['foo' => 'bar']);

        $query->addShould($should);
    }

    public function testAddMustPassesBuiltValueToQueryTreeBuilder()
    {
        $builder = self::createMock(QueryTreeBuilder::class);
        $builder->expects(self::once())->method('addMust')->with(['foo' => 'bar']);

        $query = new BoolQuery($builder);

        $should = self::createMock(QueryInterface::class);
        $should->expects(self::once())->method('build')->willReturn(['foo' => 'bar']);

        $query->addMust($should);
    }

    public function testAddFilterPassesBuiltValueToQueryTreeBuilder()
    {
        $builder = self::createMock(QueryTreeBuilder::class);
        $builder->expects(self::once())->method('addArrayParam')->with(['foo' => 'bar']);

        $query = new BoolQuery($builder);

        $should = self::createMock(QueryInterface::class);
        $should->expects(self::once())->method('build')->willReturn(['foo' => 'bar']);

        $query->addFilter($should);
    }

    public function testAddMustNotPassesBuiltValueToQueryTreeBuilder()
    {
        $builder = self::createMock(QueryTreeBuilder::class);
        $builder->expects(self::once())->method('addMustNot')->with(['foo' => 'bar']);

        $query = new BoolQuery($builder);

        $should = self::createMock(QueryInterface::class);
        $should->expects(self::once())->method('build')->willReturn(['foo' => 'bar']);

        $query->addMustNot($should);
    }

    public function testAddQueryPassesBuiltValueToQueryTreeBuilder()
    {
        $builder = self::createMock(QueryTreeBuilder::class);
        $builder->expects(self::once())->method('addArrayParam')->with(['foo' => 'bar']);

        $query = new BoolQuery($builder);

        $should = self::createMock(QueryInterface::class);
        $should->expects(self::once())->method('build')->willReturn(['foo' => 'bar']);

        $query->addQuery($should);
    }

    public function testFromPassesRootParamToQueryTreeBuilder()
    {
        $builder = self::createMock(QueryTreeBuilder::class);
        $builder->expects(self::once())->method('addRootParameter')->with('from', 1);

        $query = new BoolQuery($builder);

        $query->from(1);
    }

    public function testSizePassesRootParamToQueryTreeBuilder()
    {
        $builder = self::createMock(QueryTreeBuilder::class);
        $builder->expects(self::once())->method('addRootParameter')->with('size', 1);

        $query = new BoolQuery($builder);

        $query->size(1);
    }

    public function testMinimumShouldMatchPassesParamToQueryTreeBuilder()
    {
        $builder = self::createMock(QueryTreeBuilder::class);
        $builder->expects(self::once())->method('addParam')->with('minimum_should_match', 1);

        $query = new BoolQuery($builder);

        $query->setMinimumShouldMatch(1);
    }

    public function testBuildCallsGetTreeFromQueryTreeBuilder()
    {
        $builder = self::createMock(QueryTreeBuilder::class);
        $builder->expects(self::once())->method('getTree')->willReturn(['foo' => 'bar']);

        $query = new BoolQuery($builder);
        self::assertSame(['foo' => 'bar'], $query->build());
    }
}
