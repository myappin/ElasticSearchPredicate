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
	public function testSearchBaseParams(){
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
	public function testIndexAndBasicSearch(){
		for($i = 0; $i < 20; $i++){
			$this->_client->getElasticSearchClient()->index([
																'index' => 'test',
																'type'  => 'TestType',
																'id'    => $i,
																'body'  => [
																	'name' => 'test' . $i,
																],
															]);
		}

		$_search = $this->_client->search('test');
		$_search->setLimit(10);
		$_result = $_search->execute();
		$this->assertSame(10, count($_result['hits']['hits']));

		$_search->setLimit(10);
		$_search->setOffset(1);
		$_result = $_search->execute();
		$this->assertSame(10, count($_result['hits']['hits']));

		$_search->setLimit(10);
		$_search->setOffset(2);
		$_result = $_search->execute();
		$this->assertSame(0, count($_result['hits']['hits']));
	}


}