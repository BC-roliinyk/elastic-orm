<?php

require_once __DIR__ . '/vendor/autoload.php';
use ElasticORM\Builder\Factory\QueryFactory;
use ElasticORM\Builder\Queries\es6\HasChildQuery;

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

$shippingSqsFields = [
    'first_name^2',
    'first_name.partial',
    'last_name^2',
    'last_name.partial',
    'company^2',
    'company.partial',
    'street_1^2',
    'street_1.partial',
    'street_2^2',
    'street_2.partial',
    'city^2',
    'city.partial',
    'zip^2',
    'zip.partial',
    'country^2',
    'country.partial',
    'state^2',
    'state.partial',
    'phone^2',
    'phone.partial',
];

$customerSqsFields = [
    'company^2',
    'company.partial',
    'first_name^5',
    'first_name.partial^3',
    'last_name^5',
    'last_name.partial^3',
    'email^2',
    'email.partial',
    'phone^2',
    'phone.partial',
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
$hasChildQuery = $queryFactory->getQueryObject('HasChildQuery')
    ->setQuery($boolVariantQuery)
    ->setType('variant')
    ->setScoreMode(HasChildQuery::SCORE_MODE_MAX);


$productSqsQuery = $queryFactory
    ->getQueryObject('SimpleQueryString')
    ->setQuery($query)
    ->setLenient(true)
    ->setDefaultOperator('and')
    ->setFields($productSqsFields);

$documentTypeFilter = $queryFactory->getQueryObject('TermQuery');
$documentTypeFilter->setTerm('document_type', 'product');

$skuPrefix = $queryFactory
    ->getQueryObject('PrefixQuery')
    ->setPrefix('sku.not_analyzed', $query, 2);
$keywordsPrefix = $queryFactory
    ->getQueryObject('PrefixQuery')
    ->setPrefix('keywords.not_analyzed', $query, 1);
$namePrefix = $queryFactory
    ->getQueryObject('PrefixQuery')
    ->setPrefix('name.not_analyzed', $query, 1);


//set product root query
$boolQuery->setMinimumShouldMatch(1);
$boolQuery->addShould($hasChildQuery);
$boolQuery->addQuery($productSqsQuery);
$boolQuery->addQuery($documentTypeFilter);
$boolQuery->addQuery($storeFilter);
$boolQuery->addQuery($skuPrefix);
$boolQuery->addQuery($keywordsPrefix);
$boolQuery->addQuery($namePrefix);
$boolQuery->from(10);
$boolQuery->size(10);
$boolQuery->sort(['id']);


//----------------start customer query
$customerBoolQuery = $queryFactory->getQueryObject('BoolQuery', 'master_document');
$customerBoolQuery->setMinimumShouldMatch(1);
//shipping address query
$shippingAddressBoolQuery = $queryFactory->getQueryObject('BoolQuery');
$shippingAddressBoolQuery->setMinimumShouldMatch(1);
$shippingSqsQuery = $queryFactory
    ->getQueryObject('SimpleQueryString')
    ->setQuery($query)
    ->setLenient(true)
    ->setDefaultOperator('and')
    ->setAnalyzeWildcard(true)
    ->setFields($shippingSqsFields);
$shippingAddressBoolQuery->addQuery($shippingSqsQuery);
$shippingAddressPrefixArray = [
    'first_name.not_analyzed' => 1.5,
    'last_name.not_analyzed' => 1.5,
    'street_1.not_analyzed' => 1.5,
    'street_2.not_analyzed' => 1.5,
    'city.not_analyzed' => 1.5,
    'zip.not_analyzed' => 1.5,
    'country.not_analyzed' => 1.5,
    'state.not_analyzed' => 1.5,
    'phone.not_analyzed' => 1.5,
];
foreach ($shippingAddressPrefixArray as $prefixName => $boost) {
    $prefixQuery = $queryFactory
        ->getQueryObject('PrefixQuery')
        ->setPrefix($prefixName, $query, $boost);
    $shippingAddressBoolQuery->addQuery($prefixQuery);
}
$shippingAddressBoolQuery->addQuery($storeFilter);



$hasShippingAdressChildQuery = $queryFactory->getQueryObject('HasChildQuery')
    ->setQuery($shippingAddressBoolQuery)
    ->setType('shipping_address');
$customerBoolQuery->addShould($hasShippingAdressChildQuery);

//order child query
$orderBoolQuery = $queryFactory->getQueryObject('BoolQuery');
//TODO orderBoolQuery->addShould(setFunctionScoreQuery)
$orderBoolQuery->addQuery($storeFilter);
$orderHasChildQuery = $queryFactory->getQueryObject('HasChildQuery')
    ->setQuery($orderBoolQuery)
    ->setType('order');


$customerBoolQuery->addShould($orderHasChildQuery);




$customerSqsQuery = $queryFactory
    ->getQueryObject('SimpleQueryString')
    ->setQuery($query)
    ->setLenient(true)
    ->setDefaultOperator('and')
    ->setAnalyzeWildcard(true)
    ->setFields($variantSqsFields);
$customerBoolQuery->addShould($customerSqsQuery);

$customerPrefixArray = [
    'company.not_analyzed' => 1.5,
    'first_name.not_analyzed' => 1.5,
    'last_name.not_analyzed' => 1.5,
    'email.not_analyzed' => 1.5,
    'phone.not_analyzed' => 1.5
];
foreach ($customerPrefixArray as $prefixName => $boost) {
    $prefixQuery = $queryFactory
        ->getQueryObject('PrefixQuery')
        ->setPrefix($prefixName, $query, $boost);
    $customerBoolQuery->addShould($prefixQuery);
}
$customerBoolQuery->addQuery($storeFilter);

$documentTypeFilter = $queryFactory->getQueryObject('TermQuery');
$documentTypeFilter->setTerm('document_type', 'customer');

$customerBoolQuery->addQuery($documentTypeFilter);
$customerBoolQuery->from(10);
$customerBoolQuery->size(10);
$customerBoolQuery->sort(['id']);




var_dump($boolQuery->build());
var_dump($customerBoolQuery->build());
