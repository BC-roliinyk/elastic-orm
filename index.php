<?php

require_once __DIR__ . '/vendor/autoload.php';
use ElasticORM\Builder\Factory\QueryFactory;
$fields = ['id',
            'name^3',
            'name.partial',
            'sku^3',
            'sku.partial',
            'upc^3',
            'keywords^3',
            'keywords.partial',
            'brand_name^3'];

$queryFactory = QueryFactory::getQueryObjectFactory();
$boolQuery = $queryFactory->getQueryObject('BoolQuery', 'product');

$boolVariantQuery = $queryFactory->getQueryObject('BoolQuery', 'variant');

$stringQuery = $queryFactory->getQueryObject('SimpleQueryString')->setQuery('dsfdsf')->setFields($fields);
$stringVariantQuery = $queryFactory->getQueryObject('SimpleQueryString')->setQuery('tesy3')->setFields($fields);

$boolVariantQuery->addQuery($stringVariantQuery);

$childQuery = $queryFactory->getQueryObject('ChildQuery')->setQuery($boolVariantQuery);

$boolQuery->addQuery($stringQuery);
$boolQuery->setMinimumShouldMatch(1)
    ->addShould($childQuery)
    ->addPrefix('keywords', 'keywords', 1)
    ->addPrefix('name', 'keywords', 1)
    ->size(4)
    ->from(5);


$channelFilterQuery = $queryFactory->getQueryObject('TermQuery');
$channelFilterQuery->setTerm('channel_id', 2);

$categoriesQuery = $queryFactory->getQueryObject('TermsQuery');
$categoriesQuery->setTerms('categories', [1,2,3]);


$boolQuery->addFilter($channelFilterQuery);
$boolQuery->addFilter($categoriesQuery);

$rangeQuery = $queryFactory->getQueryObject('RangeQuery');
$rangeQuery->setRange('price', ['gte' => 'ff', 'lte' => 5]);

$boolQuery->addQuery($rangeQuery);

print_r($boolQuery->build());
