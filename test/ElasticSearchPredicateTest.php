<?php
declare(strict_types = 1);
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
use ElasticSearchPredicate\Predicate\Predicates\Match;
use ElasticSearchPredicate\Predicate\Predicates\NotMatch;


/**
 * Class ElasticSearchPredicateTest
 * @package   ElasticSearchPredicateTest
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class ElasticSearchPredicateTest extends \PHPUnit_Framework_TestCase {


	/** @var  Client */
	private $_client;


	public function setUp(){
		$this->_client = new Client();
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 */
	public function test_search_base_params(){
		$_search          = $this->_client->search();
		$_prepared_params = $_search->getPreparedParams();
		$this->assertArrayNotHasKey('index', $_prepared_params);
		$this->assertArrayNotHasKey('type', $_prepared_params);
		$this->assertArrayHasKey('body', $_prepared_params);

		$_search          = $this->_client->search('elasticsearchpredicate');
		$_prepared_params = $_search->getPreparedParams();
		$this->assertArrayHasKey('index', $_prepared_params);
		$this->assertArrayNotHasKey('type', $_prepared_params);
		$this->assertArrayHasKey('body', $_prepared_params);

		$_search          = $this->_client->search('elasticsearchpredicate', 'TestType');
		$_prepared_params = $_search->getPreparedParams();
		$this->assertArrayHasKey('index', $_prepared_params);
		$this->assertArrayHasKey('type', $_prepared_params);
		$this->assertArrayHasKey('body', $_prepared_params);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 */
	public function test_index_and_basic_search(){
		for($i = 0; $i < 50; $i++){
			$this->_client->getElasticSearchClient()->index([
																'index' => 'elasticsearchpredicate',
																'type'  => 'TestType',
																'id'    => $i + 1,
																'body'  => [
																	'name'        => 'test' . $i,
																	'test_param1' => ($i % 2 !== 0 ? 1 : 0),
																	'test_param2' => ($i % 2 === 0 ? 1 : 0),
																	'test_param3' => ($i % 5 === 0 ? 1 : 0),
																	'range_param' => $i,
																],
															]);
		}

		$this->_client->getElasticSearchClient()->indices()->refresh([
																		 'index' => 'elasticsearchpredicate',
																	 ]);
		$this->_client->getElasticSearchClient()->cluster()->health([
																		'index'           => 'elasticsearchpredicate',
																		'wait_for_status' => 'green',
																		'timeout'         => '10s',
																	]);

		$_search = $this->_client->search('elasticsearchpredicate');
		$_search->limit(10);
		$_result = $_search->execute();
		$this->assertSame(10, count($_result['hits']['hits']));

		$_search->limit(10);
		$_search->offset(4);
		$_result = $_search->execute();
		$this->assertSame(10, count($_result['hits']['hits']));

		$_search->limit(10);
		$_search->offset(5);
		$_result = $_search->execute();
		$this->assertSame(0, count($_result['hits']['hits']));

		$_search->limit(50);
		$_search->offset(null);
		$_result = $_search->execute();
		$this->assertSame(50, count($_result['hits']['hits']));


		$_search = $this->_client->search('elasticsearchpredicate', 'TestType');
		$_search->limit(10);
		$_result = $_search->execute();
		$this->assertSame(10, count($_result['hits']['hits']));

		$_search->limit(10);
		$_search->offset(4);
		$_result = $_search->execute();
		$this->assertSame(10, count($_result['hits']['hits']));

		$_search->limit(10);
		$_search->offset(5);
		$_result = $_search->execute();
		$this->assertSame(0, count($_result['hits']['hits']));

		$_search->limit(50);
		$_search->offset(null);
		$_result = $_search->execute();
		$this->assertSame(50, count($_result['hits']['hits']));
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 */
	public function test_term_predicate(){
		$_search = $this->_client->search('elasticsearchpredicate', 'TestType');
		$_search->limit(1);
		$_search->predicate->Term('name', 'test10');

		$this->assertSame([
							  'term' => [
								  'name' => 'test10',
							  ],
						  ], $_search->getQuery());

		$_result = $_search->execute();
		$this->assertSame(1, $_result['hits']['total']);
		$this->assertSame(11, intval($_result['hits']['hits'][0]['_id']));
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 */
	public function test_combined_terms_predicate(){
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

		$_result = $_search->execute();
		$this->assertSame(1, $_result['hits']['total']);
		$this->assertSame(11, intval($_result['hits']['hits'][0]['_id']));


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

		$_result = $_search->execute();
		$this->assertSame(2, $_result['hits']['total']);
		$this->assertSame(11, intval($_result['hits']['hits'][0]['_id']));
		$this->assertSame(12, intval($_result['hits']['hits'][1]['_id']));


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

		$_result = $_search->execute();
		$this->assertSame(2, $_result['hits']['total']);
		$this->assertSame(11, intval($_result['hits']['hits'][0]['_id']));
		$this->assertSame(12, intval($_result['hits']['hits'][1]['_id']));


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

		$_result = $_search->execute();
		$this->assertSame(3, $_result['hits']['total']);
		$this->assertSame(11, intval($_result['hits']['hits'][0]['_id']));
		$this->assertSame(12, intval($_result['hits']['hits'][1]['_id']));
		$this->assertSame(9, intval($_result['hits']['hits'][2]['_id']));
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 * @throws \Exception
	 */
	public function test_nesting_terms(){
		$_search = $this->_client->search('elasticsearchpredicate', 'TestType');
		$_search->limit(1);
		$_search->predicate->nest()->Term('name', 'test10')->unnest();

		$this->assertSame([
							  'term' => [
								  'name' => 'test10',
							  ],
						  ], $_search->getQuery());

		$_result = $_search->execute();
		$this->assertSame(1, $_result['hits']['total']);
		$this->assertSame(11, intval($_result['hits']['hits'][0]['_id']));


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

		$_result = $_search->execute();
		$this->assertSame(2, $_result['hits']['total']);
		$this->assertSame(3, intval($_result['hits']['hits'][0]['_id']));
		$this->assertSame(4, intval($_result['hits']['hits'][1]['_id']));
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 * @throws \Exception
	 */
	public function test_range(){
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

		$_result = $_search->execute();
		$this->assertSame(11, $_result['hits']['total']);
		$this->assertSame(11, intval($_result['hits']['hits'][0]['_id']));
		$this->assertSame(21, intval($_result['hits']['hits'][10]['_id']));
		$this->assertSame(20, intval($_result['hits']['hits'][10]['_source']['range_param']));
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 * @throws \Exception
	 */
	public function test_term_and_range(){
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

		$_result = $_search->execute();
		$this->assertSame(3, $_result['hits']['total']);
		$this->assertSame(11, intval($_result['hits']['hits'][0]['_id']));
		$this->assertSame(21, intval($_result['hits']['hits'][2]['_id']));
		$this->assertSame(20, intval($_result['hits']['hits'][2]['_source']['range_param']));
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 * @throws \Exception
	 */
	public function test_match(){
		$_search = $this->_client->search('elasticsearchpredicate', 'TestType');
		$_search->limit(2)->order('_uid', 'asc');
		$_search->predicate->Match('name', 'test1');

		$this->assertSame([
							  'match' => [
								  'name' => 'test1',
							  ],
						  ], $_search->getQuery());

		$_result = $_search->execute();
		$this->assertSame(1, $_result['hits']['total']);
		$this->assertSame(2, intval($_result['hits']['hits'][0]['_id']));
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 * @throws \Exception
	 */
	public function test_match_phrase(){
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

		$_result = $_search->execute();

		$this->assertSame(1, $_result['hits']['total']);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 * @throws \Exception
	 */
	public function test_not_match_phrase(){
		$_search = $this->_client->search('elasticsearchpredicate', 'TestType');
		$_search->limit(1);
		$_search->predicate->and((new NotMatch('name', 'test1'))->type('phrase'));

		$this->assertSame([
							  'not' => [
								  'match' => [
									  'name' => [
										  'query' => 'test1',
										  'type'  => 'phrase',
									  ],
								  ],
							  ],
						  ], $_search->getQuery());

		$_result = $_search->execute();

		$this->assertSame(49, $_result['hits']['total']);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 * @throws \Exception
	 */
	public function test_query_string(){
		$_search = $this->_client->search('elasticsearchpredicate', 'TestType');
		$_search->limit(20)->order('_uid', 'asc');
		$_search->predicate->QueryString('test1*', ['name']);

		$this->assertSame([
							  'query_string' => [
								  'query'  => 'test1*',
								  'fields' => ['name'],
							  ],
						  ], $_search->getQuery());

		$_result = $_search->execute();

		$this->assertSame(11, $_result['hits']['total']);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 * @throws \Exception
	 */
	public function test_nested_term(){
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


}