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

		$_search          = $this->_client->search('test');
		$_prepared_params = $_search->getPreparedParams();
		$this->assertArrayHasKey('index', $_prepared_params);
		$this->assertArrayNotHasKey('type', $_prepared_params);
		$this->assertArrayHasKey('body', $_prepared_params);

		$_search          = $this->_client->search('test', 'TestType');
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
																'index' => 'test',
																'type'  => 'TestType',
																'id'    => $i,
																'body'  => [
																	'name'        => 'test' . $i,
																	'test_param1' => ($i % 2 !== 0 ? 1 : 0),
																	'test_param2' => ($i % 2 === 0 ? 1 : 0),
																	'test_param3' => ($i % 5 === 0 ? 1 : 0),
																],
															]);
		}

		$_search = $this->_client->search('test');
		$_search->setLimit(10);
		$_result = $_search->execute();
		$this->assertSame(10, count($_result['hits']['hits']));

		$_search->setLimit(10);
		$_search->setOffset(4);
		$_result = $_search->execute();
		$this->assertSame(10, count($_result['hits']['hits']));

		$_search->setLimit(10);
		$_search->setOffset(5);
		$_result = $_search->execute();
		$this->assertSame(0, count($_result['hits']['hits']));
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 */
	public function test_term_predicate(){
		$_search    = $this->_client->search('test');
		$_predicate = $_search->getPredicate();
		$_predicate->Term('name', 'test0');

		$this->assertSame([
							  'term' => [
								  'name' => 'test0',
							  ],
						  ], $_search->getQuery());
	}


}