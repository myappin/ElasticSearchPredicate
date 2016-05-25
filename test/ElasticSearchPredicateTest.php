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
		$_search = $this->_client->search();
		$_search->limit(1);
		$_search->getPredicate()->Term('name', 'test10');

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
		$_search = $this->_client->search();
		$_search->limit(1);
		$_search->getPredicate()->Term('name', 'test10')->Term('test_param1', 0);

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


		$_search = $this->_client->search();
		$_search->limit(2)->order('_uid', 'asc');
		$_search->getPredicate()->Term('name', 'test10')->or->Term('name', 'test11');

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


		$_search = $this->_client->search();
		$_search->limit(2)->order('_uid', 'asc');
		$_search->getPredicate()->Term('name', 'test10')->or->Term('name', 'test11')->Term('test_param1', 1);

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


		$_search = $this->_client->search();
		$_search->limit(3)->order('_uid', 'asc');
		$_search->getPredicate()->Term('name', 'test10')->or->Term('name', 'test11')
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

}