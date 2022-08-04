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

$orderAddressSqsFields = [
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
    'email^2',
    'email.partial',
    'phone^2',
    'phone.partial',
];

$orderProductSqsFields = [
    'name^2',
    'name.partial',
    'description',
    'sku^3',
    'sku.partial',
    'keywords^2',
    'keywords.partial',
    'brand_name^2',
];

$parentCustomerSqsFields = [
    'company^2',
    'company.partial',
    'first_name^2',
    'first_name.partial',
    'last_name^2',
    'last_name.partial',
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
$boolVariantQuery->addFilter($storeFilter);

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
$boolQuery->addFilter($documentTypeFilter);
$boolQuery->addFilter($storeFilter);
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
$shippingAddressBoolQuery->addFilter($storeFilter);



$hasShippingAdressChildQuery = $queryFactory->getQueryObject('HasChildQuery')
    ->setQuery($shippingAddressBoolQuery)
    ->setType('shipping_address');
$customerBoolQuery->addShould($hasShippingAdressChildQuery);

//order child query
$childOrderBoolQuery = $queryFactory->getQueryObject('BoolQuery');
//TODO orderBoolQuery->addShould(setFunctionScoreQuery)
$childOrderBoolQuery->addFilter($storeFilter);
$orderHasChildQuery = $queryFactory->getQueryObject('HasChildQuery')
    ->setQuery($childOrderBoolQuery)
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
$customerBoolQuery->addFilter($storeFilter);

$documentTypeFilter = $queryFactory->getQueryObject('TermQuery');
$documentTypeFilter->setTerm('document_type', 'customer');

$customerBoolQuery->addFilter($documentTypeFilter);
$customerBoolQuery->from(10);
$customerBoolQuery->size(10);
$customerBoolQuery->sort(['id']);

//----------------------------------- build order query

$orderBoolQuery = $queryFactory->getQueryObject('BoolQuery', 'master_document');
$orderBoolQuery->setMinimumShouldMatch(1);
//---child order_address
$childOrderAddressBoolQuery = $queryFactory->getQueryObject('BoolQuery');
$childOrderAddressBoolQuery->setMinimumShouldMatch(1);
$childOrderAddressSqsQuery = $queryFactory
    ->getQueryObject('SimpleQueryString')
    ->setQuery($query)
    ->setLenient(true)
    ->setDefaultOperator('and')
    ->setAnalyzeWildcard(true)
    ->setFields($orderAddressSqsFields);
$childOrderAddressBoolQuery->addShould($childOrderAddressSqsQuery);
$childOrderAddressPrefixArray = [
    'first_name.not_analyzed' => 1.5,
    'last_name.not_analyzed' => 1.5,
    'company.not_analyzed' => 1.5,
    'street_1.not_analyzed' => 1.5,
    'city.not_analyzed' => 1.5,
    'country.not_analyzed' => 1.5,
    'state.not_analyzed' => 1.5,
    'email.not_analyzed' => 1.5,
    'phone.not_analyzed' => 1.5,
];
foreach ($childOrderAddressPrefixArray as $prefixName => $boost) {
    $prefixQuery = $queryFactory
        ->getQueryObject('PrefixQuery')
        ->setPrefix($prefixName, $query, $boost);
    $childOrderAddressBoolQuery->addShould($prefixQuery);
}
$childOrderAddressBoolQuery->addFilter($storeFilter);

$orderAddressHasChildQuery = $queryFactory->getQueryObject('HasChildQuery')
    ->setQuery($childOrderAddressBoolQuery)
    ->setType('order_address');

$orderBoolQuery->addShould($orderAddressHasChildQuery); //adding should with child bool order_address

//child---order_product
$childOrderProductBoolQuery = $queryFactory->getQueryObject('BoolQuery');

$orderProductHasChildQuery = $queryFactory->getQueryObject('HasChildQuery')
    ->setQuery($childOrderProductBoolQuery)
    ->setType('order_product');
$childOrderProductBoolQuery->setMinimumShouldMatch(1);

$childOrderProductSqsQuery = $queryFactory
    ->getQueryObject('SimpleQueryString')
    ->setQuery($query)
    ->setDefaultOperator('and')
    ->setAnalyzeWildcard(true)
    ->setFields($orderProductSqsFields);

$childOrderProductBoolQuery->addShould($childOrderProductSqsQuery);

$prefixProductSkuQuery = $queryFactory
    ->getQueryObject('PrefixQuery')
    ->setPrefix('sku.not_analyzed', $query, 2);
$prefixProductNameQuery = $queryFactory
    ->getQueryObject('PrefixQuery')
    ->setPrefix('name.not_analyzed', $query, 2);

$childOrderProductBoolQuery->addShould($prefixProductSkuQuery);
$childOrderProductBoolQuery->addShould($prefixProductNameQuery);

$childOrderAddressBoolQuery->addShould($prefixQuery);
$childOrderAddressBoolQuery->addFilter($storeFilter);

$orderBoolQuery->addShould($orderAddressHasChildQuery);//adding should with child bool order_product

//parent---customer
$parentCustomerBoolQuery = $queryFactory->getQueryObject('BoolQuery');
$parentCustomerBoolQuery->setMinimumShouldMatch(1);
$parentCustomerSqsQuery = $queryFactory
    ->getQueryObject('SimpleQueryString')
    ->setQuery($query)
    ->setDefaultOperator('and')
    ->setAnalyzeWildcard(true)
    ->setFields($parentCustomerSqsFields);

$parentCustomerBoolQuery->addShould($parentCustomerSqsQuery);

$parentCustomerPrefixArray = [
    'company.not_analyzed' => 1.5,
    'first_name.not_analyzed' => 1.5,
    'last_name.not_analyzed' => 1.5,
    'email.not_analyzed' => 1.5,
    'phone.not_analyzed' => 1.5
];
foreach ($parentCustomerPrefixArray as $prefixName => $boost) {
    $prefixQuery = $queryFactory
        ->getQueryObject('PrefixQuery')
        ->setPrefix($prefixName, $query, $boost);
    $parentCustomerBoolQuery->addShould($prefixQuery);
}

$parentCustomerBoolQuery->addFilter($storeFilter);

$customerHasParentQuery = $queryFactory->getQueryObject('HasParentQuery')
    ->setQuery($parentCustomerBoolQuery)
    ->setParentType('customer');

$orderBoolQuery->addShould($customerHasParentQuery);


$functionScoreQuery = $queryFactory->getQueryObject('FunctionScoreQuery');
$functionBoolQuery = $queryFactory->getQueryObject('BoolQuery');
$functionBoolQuery->setMinimumShouldMatch(1);
$sqsQuery = $queryFactory
    ->getQueryObject('SimpleQueryString')
    ->setQuery($query)
    ->setDefaultOperator('and')
    ->setAnalyzeWildcard(true)
    ->setFields($parentCustomerSqsFields);
$functionBoolQuery->addShould($sqsQuery);

$functionScoreQuery->setQuery($functionBoolQuery);
$decayQuery = $queryFactory->getQueryObject('DecayFunction');
$decayQuery->setType('gauss')->setField('created_date')->setOffset('45d')->setScale('135d')->setDecay(0.6);

$functionScoreQuery->addFunction($decayQuery);

$orderBoolQuery->addShould($functionScoreQuery);
//TODO $orderBoolQuery->addShould(PostFilter)

$orderBoolQuery->addFilter($storeFilter);

$documentTypeFilter = $queryFactory->getQueryObject('TermQuery');
$documentTypeFilter->setTerm('document_type', 'order');

$orderBoolQuery->addFilter($documentTypeFilter);
$orderBoolQuery->from(10);
$orderBoolQuery->size(10);
$orderBoolQuery->sort(['id']);

//start post filter query
$postFilterQuery = $queryFactory->getQueryObject('PostFilterQuery');
$postFilterBoolQuery = $queryFactory->getQueryObject('BoolQuery');
$postFilterBoolQueryIncomplete = $queryFactory->getQueryObject('BoolQuery');
$incompleteTermQuery = $queryFactory->getQueryObject('TermQuery')
    ->setTerm('status.not_analyzed', 'incomplete', 1);
$postFilterBoolQueryIncomplete->addMustNot($incompleteTermQuery);
$postFilterBoolQuery->addMust($postFilterBoolQueryIncomplete);
$postFilterBoolQueryIsDeleted = $queryFactory->getQueryObject('BoolQuery');
$isDeletedTermQuery = $queryFactory->getQueryObject('TermQuery')
    ->setTerm('is_deleted', true, 1);
$postFilterBoolQueryIsDeleted->addMustNot($isDeletedTermQuery);
$postFilterBoolQuery->addMust($postFilterBoolQueryIsDeleted);
$postFilterQuery->setQuery($postFilterBoolQuery);


var_dump($boolQuery->build());
var_dump($customerBoolQuery->build());
var_dump($postFilterQuery->build());
var_dump($orderBoolQuery->build());
