<?php

namespace ElasticORM\Builder\Factory;

use ElasticORM\Builder\Interfaces\QueryFactoryInterface;
use ElasticORM\Builder\Interfaces\QueryInterface;
use ElasticORM\Builder\Queries\es6\BoolQuery;
use ElasticORM\Builder\Queries\es6\DecayFunction;
use ElasticORM\Builder\Queries\es6\FieldValueFactorFunction;
use ElasticORM\Builder\Queries\es6\FunctionScoreQuery;
use ElasticORM\Builder\Queries\es6\HasChildQuery;
use ElasticORM\Builder\Queries\es6\HasParentQuery;
use ElasticORM\Builder\Queries\es6\PostFilterQuery;
use ElasticORM\Builder\Queries\es6\PrefixQuery;
use ElasticORM\Builder\Queries\es6\RandomScoreFunction;
use ElasticORM\Builder\Queries\es6\ScriptScoreFunction;
use ElasticORM\Builder\Queries\es6\SimpleQueryString;
use ElasticORM\Builder\Queries\es6\TermQuery;
use ElasticORM\Builder\Queries\es6\TermsQuery;
use ElasticORM\Builder\Queries\es6\RangeQuery;
use ElasticORM\Builder\Queries\es6\WeightFunction;
use ElasticORM\Builder\QueryTreeBuilder;

class Es6QueryFactory implements QueryFactoryInterface
{
    /**
     * @throws \Exception
     */
    public function getQueryObject(string $queryType, string $entityType = null): QueryInterface
    {
        $queryTreeBuilder = $this->getQueryTreeBuilderObject($queryType, $entityType);
        switch ($queryType) {
            case 'BoolQuery':
                return new BoolQuery($queryTreeBuilder);
            case 'SimpleQueryString':
                return new SimpleQueryString();
            case 'TermQuery':
                return new TermQuery();
            case 'TermsQuery':
                return new TermsQuery();
            case 'RangeQuery':
                return new RangeQuery();
            case 'HasChildQuery':
                return new HasChildQuery();
            case 'HasParentQuery':
                return new HasParentQuery();
            case 'PostFilterQuery':
                return new PostFilterQuery();
            case 'PrefixQuery':
                return new PrefixQuery();
            case 'FunctionScoreQuery':
                return new FunctionScoreQuery();
            case 'DecayFunction':
                return new DecayFunction();
            case 'FieldValueFactorFunction':
                return new FieldValueFactorFunction();
            case 'RandomScoreFunction':
                return new RandomScoreFunction();
            case 'ScriptScoreFunction':
                return new ScriptScoreFunction();
            case 'WeightFunction':
                return new WeightFunction();
            default:
                throw new \Exception('Query Class' . $queryType . 'not found');
        }
        return new BoolQuery($queryTreeBuilder);
    }

    public function getQueryTreeBuilderObject(string $queryType, ?string $entityType): QueryTreeBuilder
    {
        switch ($queryType) {
            case 'BoolQuery':
                return new QueryTreeBuilder('bool', $entityType);
        }
        return new QueryTreeBuilder($queryType, $entityType);
    }
}
