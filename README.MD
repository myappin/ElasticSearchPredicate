[![status](https://travis-ci.org/myappin/ElasticSearchPredicate.svg?branch=master)](https://travis-ci.org/myappin/ElasticSearchPredicate)
ElasticSearchPredicate
======================
is simple PHP API fr ElasticSearch. It simulates Zend-Db predicates.

Install
=======
```
composer: [HERE](https://packagist.org/packages/myappin/elasticsearchpredicate)
```
OR
```
gic clone https://github.com/myappin/ElasticSearchPredicate.git
```

Usage
=====

**Adding predicates**
`addPredicate(PredicateInterface $predicate)`, `andPredicate(PredicateInterface $predicate)`, `orPredicate(PredicateInterface $predicate)`, `and(PredicateInterface $predicate)`, `or(PredicateInterface $predicate)`

**Basic predicates**
```php
$_client = new Client();
$_search = $_client->search();

$_search->limit(10)->order('_uid', 'asc')->predicate->Term('name', 'SomeName')->or->Term('price', 1000);
```

**Nesting**
```php
$_client = new Client();
$_search = $_client->search();

$_search->limit(10)->order('_uid', 'asc')->predicate->nest()->Term('name', 'SomeName')->or->Term('name', 'SomeOtherName')->unnest()->Term('price', 1000);
```

**Boosting**
```php
$_client = new Client();
$_search = $_client->search();

$_search->limit(10)->order('_uid', 'asc')->predicate->andPredicate((new Term('name', 'SomeName'))->boost(2))->or->Term('name', 'SomeOtherName');
//or
$_search->limit(10)->order('_uid', 'asc')->predicate->Term('name', 'SomeName', ['boost' => 2])->or->Term('name', 'SomeOtherName');
```