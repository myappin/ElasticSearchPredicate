<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 23. 5. 2016
 * Time: 14:15
 */

namespace ElasticSearchPredicateTest;

use ElasticSearchPredicate\Client;
use ElasticSearchPredicate\Predicate\FunctionScore\Decay;
use ElasticSearchPredicate\Predicate\FunctionScore\Field\Field;
use ElasticSearchPredicate\Predicate\FunctionScore\FieldValueFactor;
use ElasticSearchPredicate\Predicate\FunctionScore\ScriptScore;
use ElasticSearchPredicate\Predicate\Predicates\Match;
use ElasticSearchPredicate\Predicate\PredicateSet;

/**
 * Class ElasticSearchPredicateTest
 * @package   ElasticSearchPredicateTest
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class ElasticSearchPredicateTest extends \PHPUnit_Framework_TestCase {


    /** @var  Client */
    private $_client;


    public function setUp() {
        $this->_client = new Client();
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_search_base_params() {
        $_search = $this->_client->search();
        $_prepared_params = $_search->getPreparedParams();
        $this->assertArrayNotHasKey('index', $_prepared_params);
        $this->assertArrayNotHasKey('type', $_prepared_params);
        $this->assertArrayHasKey('body', $_prepared_params);

        $_search = $this->_client->search('elasticsearchpredicate');
        $_prepared_params = $_search->getPreparedParams();
        $this->assertArrayHasKey('index', $_prepared_params);
        $this->assertArrayNotHasKey('type', $_prepared_params);
        $this->assertArrayHasKey('body', $_prepared_params);

        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_prepared_params = $_search->getPreparedParams();
        $this->assertArrayHasKey('index', $_prepared_params);
        $this->assertArrayHasKey('type', $_prepared_params);
        $this->assertArrayHasKey('body', $_prepared_params);
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_term_predicate() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(1);
        $_search->predicate->Term('name', 'test10');

        $this->assertSame([
            'term' => [
                'name' => 'test10',
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_combined_terms_predicate() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(1);
        $_search->predicate->Term('name', 'test10')->Term('test_param1', 0);

        $this->assertSame([
            'bool' => [
                'must' => [
                    [
                        'term' => [
                            'name' => 'test10',
                        ],
                    ],
                    [
                        'term' => [
                            'test_param1' => 0,
                        ],
                    ],
                ],
            ],
        ], $_search->getQuery());

        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(2)->order('_uid', 'asc');
        $_search->predicate->Term('name', 'test10')->or->Term('name', 'test11');

        $this->assertSame([
            'bool' => [
                'should' => [
                    [
                        'term' => [
                            'name' => 'test10',
                        ],
                    ],
                    [
                        'term' => [
                            'name' => 'test11',
                        ],
                    ],
                ],
            ],
        ], $_search->getQuery());

        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(2)->order('_uid', 'asc');
        $_search->predicate->Term('name', 'test10')->or->Term('name', 'test11')->Term('test_param1', 1);

        $this->assertSame([
            'bool' => [
                'should' => [
                    [
                        'term' => [
                            'name' => 'test10',
                        ],
                    ],
                    [
                        'bool' => [
                            'must' => [
                                [
                                    'term' => [
                                        'name' => 'test11',
                                    ],
                                ],
                                [
                                    'term' => [
                                        'test_param1' => 1,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $_search->getQuery());

        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(3)->order('_uid', 'asc');
        $_search->predicate->Term('name', 'test10')->or->Term('name', 'test11')
            ->Term('test_param1', 1)->or->Term('name', 'test8');

        $this->assertSame([
            'bool' => [
                'should' => [
                    [
                        'term' => [
                            'name' => 'test10',
                        ],
                    ],
                    [
                        'bool' => [
                            'must' => [
                                [
                                    'term' => [
                                        'name' => 'test11',
                                    ],
                                ],
                                [
                                    'term' => [
                                        'test_param1' => 1,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'term' => [
                            'name' => 'test8',
                        ],
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_nesting_terms() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(1);
        $_search->predicate->nest()->Term('name', 'test10')->unnest();

        $this->assertSame([
            'term' => [
                'name' => 'test10',
            ],
        ], $_search->getQuery());

        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(2)->order('_uid', 'asc');
        $_search->predicate->nest()->Term('name', 'test2')->or->Term('name', 'test3')->unnest()->Term('test_param3', 0);

        $this->assertSame([
            'bool' => [
                'must' => [
                    [
                        'bool' => [
                            'should' => [
                                [
                                    'term' => [
                                        'name' => 'test2',
                                    ],
                                ],
                                [
                                    'term' => [
                                        'name' => 'test3',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'term' => [
                            'test_param3' => 0,
                        ],
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_range() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(20)->order('range_param', 'asc');
        $_search->predicate->Range('range_param', 10, 20);

        $this->assertSame([
            'range' => [
                'range_param' => [
                    'gte' => 10,
                    'lte' => 20,
                ],
            ],
        ], $_search->getQuery());

        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(20)->order('range_param', 'asc');
        $_search->predicate->Range('range_param', 10);

        $this->assertSame([
            'range' => [
                'range_param' => [
                    'gte' => 10,
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_term_and_range() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(20)->order('range_param', 'asc');
        $_search->predicate->Range('range_param', 10, 20)->Term('test_param3', 1);

        $this->assertSame([
            'bool' => [
                'must' => [
                    [
                        'range' => [
                            'range_param' => [
                                'gte' => 10,
                                'lte' => 20,
                            ],
                        ],
                    ],
                    [
                        'term' => [
                            'test_param3' => 1,
                        ],
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_match() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(2)->order('_uid', 'asc');
        $_search->predicate->Match('name', 'test1');

        $this->assertSame([
            'match' => [
                'name' => 'test1',
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_match_phrase() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(1);
        $_search->predicate->and((new Match('name', 'test1'))->type('phrase'));

        $this->assertSame([
            'match' => [
                'name' => [
                    'query' => 'test1',
                    'type'  => 'phrase',
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_not_match_phrase() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(1);
        $_search->predicate->not()->Match('name', 'test1')->unnest();

        $this->assertSame([
            'bool' => [
                'must_not' => [
                    [
                        'match' => [
                            'name' => 'test1',
                        ],
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_not_append_match_phrase() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(1);
        $_search->predicate->not()->append((new PredicateSet())->Match('name', 'test1'))->unnest();

        $this->assertSame([
            'bool' => [
                'must_not' => [
                    [
                        'match' => [
                            'name' => 'test1',
                        ],
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_appends_with_combiner() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(1);
        $_search->predicate->not()->append((new PredicateSet())->Match('name', 'test1'))->unnest()->OR->nest()
            ->append((new PredicateSet())->Match('name', 'test2'));

        $this->assertSame([
            'bool' =>
                [
                    'should' =>
                        [
                            [
                                'bool' =>
                                    [
                                        'must_not' =>
                                            [
                                                [
                                                    'match' =>
                                                        [
                                                            'name' => 'test1',
                                                        ],
                                                ],
                                            ],
                                    ],
                            ],
                            [
                                'match' =>
                                    [
                                        'name' => 'test2',
                                    ],
                            ],
                        ],
                ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_not_term() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(1);
        $_search->predicate->not()->Term('name', 'test1')->unnest();

        $this->assertSame([
            'bool' => [
                'must_not' => [
                    [
                        'term' => [
                            'name' => 'test1',
                        ],
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_not_term_combination() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(1);
        $_search->predicate->not()->Term('name', 'test1')->Term('name', 'test2')->unnest();

        $this->assertSame([
            'bool' => [
                'must_not' => [
                    [
                        'term' => [
                            'name' => 'test1',
                        ],
                    ],
                    [
                        'term' => [
                            'name' => 'test2',
                        ],
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_query_string() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(20)->order('_uid', 'asc');
        $_search->predicate->QueryString('test1*', ['name']);

        $this->assertSame([
            'query_string' => [
                'query'  => 'test1*',
                'fields' => ['name'],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_nested_term() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->nest()
            ->nested('nested_path')
            ->Term('name', 'test1')
            ->Term('test_param1', 1)
            ->unnest()->or->nested('nested_path')
            ->Term('name', 'test2')
            ->Term('test_param1', 0)
            ->unnest()
            ->unnest();

        $this->assertSame([
            'bool' => [
                'should' => [
                    [
                        'nested' => [
                            'path'  => 'nested_path',
                            'query' => [
                                'bool' => [
                                    'must' => [
                                        [
                                            'term' => [
                                                'name' => 'test1',
                                            ],
                                        ],
                                        [
                                            'term' => [
                                                'test_param1' => 1,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'nested' => [
                            'path'  => 'nested_path',
                            'query' => [
                                'bool' => [
                                    'must' => [
                                        [
                                            'term' => [
                                                'name' => 'test2',
                                            ],
                                        ],
                                        [
                                            'term' => [
                                                'test_param1' => 0,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_multimatch_string() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(10);
        $_search->predicate->MultiMatch(1, [
            'test_param1',
            'test_param3',
        ], ['type' => 'phrase']);

        $this->assertSame([
            'multi_match' => [
                'query'  => 1,
                'fields' => [
                    'test_param1',
                    'test_param3',
                ],
                'type'   => 'phrase',
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_range_types() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(10);
        $_search->predicate->Range('range_param', 1, 2, [
            'types' => [
                'from' => 'gt',
                'to'   => 'lt',
            ],
        ]);

        $this->assertSame([
            'range' => [
                'range_param' => [
                    'gt' => 1,
                    'lt' => 2,
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_fields() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(1)->fields(['name']);
        $_search->predicate->Term('name', 'test10');

        $this->assertSame([
            'index' => 'elasticsearchpredicate',
            'type'  => 'TestType',
            'size'  => 1,
            'body'  => [
                'stored_fields' => ['name'],
                'query'         => [
                    'term' => [
                        'name' => 'test10',
                    ],
                ],
            ],
        ], $_search->getPreparedParams());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_decay_function_score() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');

        $_linear = (new Decay('linear'))->addField(new Field('range_param', 1, 2))
            ->addField(new Field('range_param', 2, 4));
        $_linear->predicate->Range('range_param', 1, 5);

        $_search->function_score->addFunction($_linear);

        $_linear->setWeight(1);

        $this->assertSame([
            'function_score' => [
                'functions' => [
                    [
                        'linear' => [
                            'range_param' => [
                                'origin' => 2,
                                'scale'  => 4,
                            ],
                        ],
                        'filter' => [
                            'range' => [
                                'range_param' => [
                                    'gte' => 1,
                                    'lte' => 5,
                                ],
                            ],
                        ],
                        'weight' => 1,
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_script_score_function_score() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');

        $_linear = (new ScriptScore([
            'lang'   => 'groovy',
            'inline' => '_score * doc["range_param"].value / pow(param1, param2)',
        ], [
            'param1' => 2,
            'param2' => 3,
        ]));
        $_search->function_score->addFunction($_linear);

        $this->assertSame([
            'function_score' => [
                'functions' => [
                    [
                        'script_score' => [
                            'script' => [
                                'lang'   => 'groovy',
                                'inline' => '_score * doc["range_param"].value / pow(param1, param2)',
                                'params' => [
                                    'param1' => 2,
                                    'param2' => 3,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_field_value_factor_function_score() {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');

        $_factor = new FieldValueFactor('range_param', 1.2, 'sqrt');
        $_search->function_score->addFunction($_factor);

        $this->assertSame([
            'function_score' => [
                'functions' => [
                    [
                        'field_value_factor' => [
                            'field'    => 'range_param',
                            'factor'   => 1.2,
                            'modifier' => 'sqrt',
                        ],
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     */
    public function test_delete_by_query() {
        $_search = $this->_client->delete('elasticsearchpredicate', 'TestType');

        $_search->predicate->Range('range_param', 10, 20);

        $this->assertSame([
            'index'     => 'elasticsearchpredicate',
            'type'      => 'TestType',
            'conflicts' => 'proceed',
            'body'      => [
                'query' => [
                    'range' => [
                        'range_param' => [
                            'gte' => 10,
                            'lte' => 20,
                        ],
                    ],
                ],
            ],
        ], $_search->getPreparedParams());
    }


}
