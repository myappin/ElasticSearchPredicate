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
use ElasticSearchPredicate\Predicate\FunctionScore;
use ElasticSearchPredicate\Predicate\FunctionScore\Decay;
use ElasticSearchPredicate\Predicate\FunctionScore\Field\Field;
use ElasticSearchPredicate\Predicate\FunctionScore\FieldValueFactor;
use ElasticSearchPredicate\Predicate\FunctionScore\ScriptScore;
use ElasticSearchPredicate\Predicate\Predicates\MatchSome;
use ElasticSearchPredicate\Predicate\PredicateSet;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class ElasticSearchPredicateTest
 * @package   ElasticSearchPredicateTest
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class ElasticSearchPredicateTest extends TestCase {


    /** @var  Client */
    private Client $_client;


    public function setUp(): void {
        $this->_client = new Client();
    }


    /**
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_appends_with_combiner(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->not()->append((new PredicateSet())->Match('name', 'test1'))->unnest()->OR->nest()
            ->append((new PredicateSet())->Match('name', 'test2'));

        self::assertSame([
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
     */
    public function test_combined_terms_predicate(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->Term('name', 'test10')->Term('test_param1', 0);

        self::assertSame([
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
        $_search->predicate->Term('name', 'test10')->or->Term('name', 'test11');

        self::assertSame([
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
        $_search->predicate->Term('name', 'test10')->or->Term('name', 'test11')->Term('test_param1', 1);

        self::assertSame([
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
        $_search->predicate->Term('name', 'test10')->or->Term('name', 'test11')
            ->Term('test_param1', 1)->or->Term('name', 'test8');

        self::assertSame([
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
     */
    public function test_decay_function_score(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');

        $_linear = (new Decay('linear'))->addField(new Field('range_param', 1, 2))
            ->addField(new Field('range_param', 2, 4));
        $_linear->predicate->Range('range_param', 1, 5);
        $_linear->setWeight(1);

        $_function_score = new FunctionScore();
        $_function_score->addFunction($_linear);
        $_function_score->MatchAll();

        $_search->predicate->append($_function_score);

        self::assertSame(serialize([
            'function_score' => [
                'query'     => [
                    'match_all' => new stdClass(),
                ],
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
        ]), serialize($_search->getQuery()));
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_delete_by_query(): void {
        $_search = $this->_client->delete('elasticsearchpredicate', 'TestType');

        $_search->predicate->Range('range_param', 10, 20);

        self::assertSame([
            'index' => [
                [
                    'index'             => 'elasticsearchpredicate',
                    'wait_for_response' => true,
                ],
            ],
            'type'  => 'TestType',
            'body'  => [
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


    /**
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_field_value_factor_function_score(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');

        $_factor = new FieldValueFactor('range_param', 1.2, 'sqrt');

        $_function_score = new FunctionScore();
        $_function_score->addFunction($_factor);
        $_function_score->MatchAll();

        $_search->predicate->append($_function_score);

        self::assertSame(serialize([
            'function_score' => [
                'query'     => [
                    'match_all' => new stdClass(),
                ],
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
        ]), serialize($_search->getQuery()));
    }


    /**
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_fields(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(10)->fields(['name']);
        $_search->predicate->Term('name', 'test10');

        self::assertSame([
            'index' => 'elasticsearchpredicate',
            'type'  => 'TestType',
            'size'  => 10,
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
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_has_child(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate
            ->child('ChildType')
            ->Term('test_param1', 1)
            ->unnest();

        self::assertSame([
            'has_child' => [
                'type'  => 'ChildType',
                'query' => [
                    'term' => [
                        'test_param1' => 1,
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_has_child_function_score(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');

        $_function_score = new FunctionScore();
        $_function_score->addFunction(new ScriptScore([
            'lang'   => 'groovy',
            'inline' => 'TEST SCRIPT',
        ], [
            'test_param1' => 1,
        ]));
        $_function_score->Term('test_param2', 1);

        $_search->predicate
            ->child('ChildType')
            ->append($_function_score)
            ->unnest();

        self::assertSame([
            'has_child' => [
                'type'  => 'ChildType',
                'query' => [
                    'function_score' => [
                        'query'     => [
                            'term' => [
                                'test_param2' => 1,
                            ],
                        ],
                        'functions' => [
                            [
                                'script_score' => [
                                    'script' => [
                                        'lang'   => 'groovy',
                                        'inline' => 'TEST SCRIPT',
                                        'params' => [
                                            'test_param1' => 1,
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
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_has_parent(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate
            ->parent('ParentType')
            ->Term('test_param1', 1)
            ->unnest();

        self::assertSame([
            'has_parent' => [
                'parent_type' => 'ParentType',
                'query'       => [
                    'term' => [
                        'test_param1' => 1,
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_has_parent_function_score(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');

        $_function_score = new FunctionScore();
        $_function_score->addFunction(new ScriptScore([
            'lang'   => 'groovy',
            'inline' => 'TEST SCRIPT',
        ], [
            'test_param1' => 1,
        ]));
        $_function_score->Term('test_param2', 1);

        $_search->predicate
            ->parent('ParentType')
            ->append($_function_score)
            ->unnest();

        self::assertSame([
            'has_parent' => [
                'parent_type' => 'ParentType',
                'query'       => [
                    'function_score' => [
                        'query'     => [
                            'term' => [
                                'test_param2' => 1,
                            ],
                        ],
                        'functions' => [
                            [
                                'script_score' => [
                                    'script' => [
                                        'lang'   => 'groovy',
                                        'inline' => 'TEST SCRIPT',
                                        'params' => [
                                            'test_param1' => 1,
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
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_limit_offset(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->limit(10)->offset(10);
        $_search->predicate->Term('name', 'test10');

        self::assertSame([
            'index' => 'elasticsearchpredicate',
            'type'  => 'TestType',
            'size'  => 10,
            'from'  => 100,
            'body'  => [
                'query' => [
                    'term' => [
                        'name' => 'test10',
                    ],
                ],
            ],
        ], $_search->getPreparedParams());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_match(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->Match('name', 'test1');

        self::assertSame([
            'match' => [
                'name' => 'test1',
            ],
        ], $_search->getQuery());
    }


    /**
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_match_phrase(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->and((new MatchSome('name', 'test1'))->type('phrase'));

        self::assertSame([
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
     */
    public function test_multimatch_string(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->MultiMatch(1, [
            'test_param1',
            'test_param3',
        ], ['type' => 'phrase']);

        self::assertSame([
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
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_nested_term(): void {
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

        self::assertSame([
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
                                                'nested_path.name' => 'test1',
                                            ],
                                        ],
                                        [
                                            'term' => [
                                                'nested_path.test_param1' => 1,
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
                                                'nested_path.name' => 'test2',
                                            ],
                                        ],
                                        [
                                            'term' => [
                                                'nested_path.test_param1' => 0,
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

        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->nest()
            ->nested('nested_path')
            ->Term('nested_path.name', 'test1')
            ->Term('nested_path.test_param1', 1)
            ->unnest()->or->nested('nested_path')
            ->Term('nested_path.name', 'test2')
            ->Term('nested_path.test_param1', 0)
            ->unnest()
            ->unnest();

        self::assertSame([
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
                                                'nested_path.name' => 'test1',
                                            ],
                                        ],
                                        [
                                            'term' => [
                                                'nested_path.test_param1' => 1,
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
                                                'nested_path.name' => 'test2',
                                            ],
                                        ],
                                        [
                                            'term' => [
                                                'nested_path.test_param1' => 0,
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

        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate
            ->nested('nested_path1')
            ->nested('nested_path2')
            ->Term('name', 'test1')
            ->Term('nested_path1.nested_path2.test_param1', 1)
            ->append(
                (new PredicateSet())
                    ->Term('name2', 'test2')
                    ->Term('test_param2', 2)
            )
            ->unnest()
            ->unnest();

        self::assertSame([
            'nested' => [
                'path'  => 'nested_path1',
                'query' => [
                    'nested' => [
                        'path'  => 'nested_path1.nested_path2',
                        'query' => [
                            'bool' => [
                                'must' => [
                                    [
                                        'term' => [
                                            'nested_path1.nested_path2.name' => 'test1',
                                        ],
                                    ],
                                    [
                                        'term' => [
                                            'nested_path1.nested_path2.test_param1' => 1,
                                        ],
                                    ],
                                    [
                                        'bool' => [
                                            'must' => [
                                                [
                                                    'term' => [
                                                        'nested_path1.nested_path2.name2' => 'test2',
                                                    ],
                                                ],
                                                [
                                                    'term' => [
                                                        'nested_path1.nested_path2.test_param2' => 2,
                                                    ],
                                                ],
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
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_nesting_terms(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->nest()->Term('name', 'test10')->unnest();

        self::assertSame([
            'term' => [
                'name' => 'test10',
            ],
        ], $_search->getQuery());

        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->nest()->Term('name', 'test2')->or->Term('name', 'test3')->unnest()->Term('test_param3', 0);

        self::assertSame([
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
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_not_append_match_phrase(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->not()->append((new PredicateSet())->Match('name', 'test1'))->unnest();

        self::assertSame([
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
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_not_match_phrase(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->not()->Match('name', 'test1')->unnest();

        self::assertSame([
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
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_not_term(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->not()->Term('name', 'test1')->unnest();

        self::assertSame([
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
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_not_term_combination(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->not()->Term('name', 'test1')->Term('name', 'test2')->unnest();

        self::assertSame([
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
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \Exception
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_order(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->order('name', 'ASC');
        $_search->predicate->Term('name', 'test10');

        self::assertSame([
            'index' => 'elasticsearchpredicate',
            'type'  => 'TestType',
            'body'  => [
                'query' => [
                    'term' => [
                        'name' => 'test10',
                    ],
                ],
                'sort'  => [
                    [
                        'name' => 'asc',
                    ],
                ],
            ],
        ], $_search->getPreparedParams());

        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->order('name', 'ASC')->order('surname', 'desc');
        $_search->predicate->Term('name', 'test10');

        self::assertSame([
            'index' => 'elasticsearchpredicate',
            'type'  => 'TestType',
            'body'  => [
                'query' => [
                    'term' => [
                        'name' => 'test10',
                    ],
                ],
                'sort'  => [
                    [
                        'name' => 'asc',
                    ],
                    [
                        'surname' => 'desc',
                    ],
                ],
            ],
        ], $_search->getPreparedParams());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_query_string(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->QueryString('test1*', ['name']);

        self::assertSame([
            'query_string' => [
                'query'  => 'test1*',
                'fields' => ['name'],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_range(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->Range('range_param', 10, 20);

        self::assertSame([
            'range' => [
                'range_param' => [
                    'gte' => 10,
                    'lte' => 20,
                ],
            ],
        ], $_search->getQuery());

        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->Range('range_param', 10);

        self::assertSame([
            'range' => [
                'range_param' => [
                    'gte' => 10,
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_range_types(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->Range('range_param', 1, 2, [
            'types' => [
                'from' => 'gt',
                'to'   => 'lt',
            ],
        ]);

        self::assertSame([
            'range' => [
                'range_param' => [
                    'gt' => 1,
                    'lt' => 2,
                ],
            ],
        ], $_search->getQuery());
    }


    /**
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_script_score_function_score(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');

        $_linear = (new ScriptScore([
            'lang'   => 'groovy',
            'inline' => '_score * doc["range_param"].value / pow(param1, param2)',
        ], [
            'param1' => 2,
            'param2' => 3,
        ]));

        $_function_score = new FunctionScore();
        $_function_score->addFunction($_linear);
        $_function_score->MatchAll();

        $_search->predicate->append($_function_score);

        self::assertSame(serialize([
            'function_score' => [
                'query'     => [
                    'match_all' => new stdClass(),
                ],
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
        ]), serialize($_search->getQuery()));
    }


    /**
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_search_base_params(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_prepared_params = $_search->getPreparedParams();
        self::assertArrayHasKey('index', $_prepared_params);
        self::assertArrayHasKey('type', $_prepared_params);
        self::assertArrayHasKey('body', $_prepared_params);
    }


    /**
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_skip_append_empty(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_predicate_set = new PredicateSet();
        $_predicate_set = $_predicate_set->Match('name', 'test1')
            ->nest()
            ->append(new PredicateSet())->OR->append(new PredicateSet())->unnest();

        $_search->predicate->not()->append($_predicate_set)->unnest();

        self::assertSame([
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
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_switch_combiner(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');

        $_predicate_set = $_search->predicate;

        // @off
        $_predicate_set
            ->nest()
                ->nest()
                    ->Match('name', 'test1')
                    ->Match('name', 'test2')
                ->unnest()
                ->OR
                ->Match('name', 'test2')
            ->unnest()
            ->nest()
                ->Match('name', 'test3')
                ->Match('name', 'test4')
            ->unnest();
        // @on

        self::assertSame([
            'bool' =>
                [
                    'must' =>
                        [
                            [
                                'bool' =>
                                    [
                                        'should' =>
                                            [
                                                [
                                                    'bool' =>
                                                        [
                                                            'must' =>
                                                                [
                                                                    [
                                                                        'match' =>
                                                                            [
                                                                                'name' => 'test1',
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
                                                ],
                                                [
                                                    'match' =>
                                                        [
                                                            'name' => 'test2',
                                                        ],
                                                ],
                                            ],
                                    ],
                            ],
                            [
                                'bool' =>
                                    [
                                        'must' =>
                                            [
                                                [
                                                    'match' =>
                                                        [
                                                            'name' => 'test3',
                                                        ],
                                                ],
                                                [
                                                    'match' =>
                                                        [
                                                            'name' => 'test4',
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
     */
    public function test_term_and_range(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->Range('range_param', 10, 20)->Term('test_param3', 1);

        self::assertSame([
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
     */
    public function test_term_predicate(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->Term('name', 'test10');

        self::assertSame([
            'term' => [
                'name' => 'test10',
            ],
        ], $_search->getQuery());
    }
    
    
    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function test_hooks(): void {
        $_search = $this->_client->search('elasticsearchpredicate', 'TestType');
        $_search->predicate->Term('name', 'test1')->or->Term('name', 'test2');
        $_search->predicate->addHook(PredicateSet::HOOK_BEFORE_TO_ARRAY, static function (PredicateSet $predicate_set): PredicateSet {
            $_new_predicate_set = new PredicateSet();
            
            $_new_predicate_set
                ->equalTo('APP_ID', 'APP_ID_VALUE');
            
            $_new_predicate_set->append($predicate_set);
            
            return $_new_predicate_set;
        });
        
        self::assertSame([
            'bool' => [
                'must' => [
                    [
                        'term' => [
                            'APP_ID' => 'APP_ID_VALUE',
                        ],
                    ],
                    [
                        'bool' => [
                            'should' => [
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
                    ],
                ],
            ],
        ], $_search->getQuery());
    }


}
