<?php

require_once __DIR__ . '/vendor/autoload.php';
use ElasticORM\Builder\Factory\QueryFactory;
$query = 'something';
$productSqsFields = [
    'id',
    'name^3',
    'name.partial',
    'sku^3',
    'sku.partial',
    'upc^3',
    'keywords^3',
    'keywords.partial',
    'brand_name^3',
];

$variantSqsFields = [
    'sku.not_analyzed^3',
    'sku.partial',
    'sku',
    'gtin',
];




$queryFactory = QueryFactory::getQueryObjectFactory();

$storeFilter = $queryFactory->getQueryObject('TermQuery');
$storeFilter->setTerm('store_id', 1000000);

$boolQuery = $queryFactory->getQueryObject('BoolQuery', 'master_document');//root query

$boolVariantQuery = $queryFactory->getQueryObject('BoolQuery');

$variantSqsQuery = $queryFactory
    ->getQueryObject('SimpleQueryString')
    ->setQuery($query)
    ->setLenient(true)
    ->setDefaultOperator('and')
    ->setFields($variantSqsFields);

//fill the variant bool query
$boolVariantQuery->setMinimumShouldMatch(1);
$boolVariantQuery->addQuery($variantSqsQuery);
$boolVariantQuery->addQuery($storeFilter);

//set variant bool query as child
$childQuery = $queryFactory->getQueryObject('ChildQuery')
    ->setQuery($boolVariantQuery)
    ->setType('variant')
    ->setScoreMode('max');


$productSqsQuery = $queryFactory
    ->getQueryObject('SimpleQueryString')
    ->setQuery($query)
    ->setLenient(true)
    ->setDefaultOperator('and')
    ->setFields($productSqsFields);

$documentTypeFilter = $queryFactory->getQueryObject('TermQuery');
$documentTypeFilter->setTerm('document_type', 'product');



//set root query
$boolQuery->setMinimumShouldMatch(1);
$boolQuery->addShould($childQuery);
$boolQuery->addQuery($productSqsQuery);
$boolQuery->addQuery($documentTypeFilter);
$boolQuery->addQuery($storeFilter);
$boolQuery->addPrefix('sku.not_analyzed', $query, 2);
$boolQuery->addPrefix('keywords.not_analyzed', $query, 1);
$boolQuery->addPrefix('name.not_analyzed', $query, 1);
$boolQuery->from(10);
$boolQuery->size(10);
$boolQuery->sort(['id']);




var_dump($boolQuery->build());
