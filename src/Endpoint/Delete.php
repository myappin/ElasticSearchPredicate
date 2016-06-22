<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 23. 5. 2016
 * Time: 13:48
 */

namespace ElasticSearchPredicate\Endpoint;


use Elasticsearch\Client;
use ElasticSearchPredicate\Endpoint\Query\QueryInterface;
use ElasticSearchPredicate\Endpoint\Query\QueryTrait;
use ElasticSearchPredicate\Predicate\FunctionScore;
use ElasticSearchPredicate\Predicate\PredicateSet;
use ElasticSearchPredicate\Predicate\PredicateSetInterface;

/**
 * Class Delete
 * @package   ElasticSearchPredicate\Endpoint
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @property PredicateSet predicate
 * @method Search Term(string $term, $value, array $options = [])
 * @method Search Match(string $match, $query, array $options = [])
 * @method Search Range(string $term, $from, $to = null, array $options = [])
 * @method Search QueryString($query, array $fields = [], array $options = [])
 * @property PredicateSet AND
 * @property PredicateSet and
 * @property PredicateSet OR
 * @property PredicateSet or
 */
class Delete implements EndpointInterface, QueryInterface {


	use QueryTrait;


	/**
	 * @var string
	 */
	protected $_index;


	/**
	 * @var string
	 */
	protected $_type;


	/**
	 * @var \Elasticsearch\Client
	 */
	protected $_client;


	/**
	 * @var array
	 */
	protected $_prepared_params;


	/**
	 * @var bool
	 */
	protected $_is_prepared = false;


	/**
	 * @var \Exception
	 */
	protected $_exception;


	/**
	 * SearchPredicate constructor.
	 * @param \Elasticsearch\Client $client
	 * @param string                $index
	 * @param string                $type
	 */
	public function __construct(Client $client, string $index = '', string $type = ''){
		$this->_client = $client;
		$this->_index  = $index;
		$this->_type   = $type;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $name
	 * @param $arguments
	 * @return PredicateSetInterface
	 */
	public function __call($name, $arguments) : PredicateSetInterface{
		if(empty($arguments)){
			return call_user_func([
									  $this->getPredicate(),
									  $name,
								  ]);
		}
		else{
			return call_user_func_array([
											$this->getPredicate(),
											$name,
										], $arguments);
		}
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $name
	 * @return \ElasticSearchPredicate\Predicate\PredicateSetInterface
	 */
	public function __get($name) : PredicateSetInterface{
		$_name = strtolower($name);
		if($_name === 'predicate'){
			return $this->getPredicate();
		}

		return $this->getPredicate()->{$name};
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function getPreparedParams() : array{
		if(!$this->_is_prepared){
			$this->prepareParams();
		}

		return $this->_prepared_params;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 * @throws \Exception
	 */
	public function execute() : array{
		try{
			$_result = $this->_client->deleteByQuery($this->getPreparedParams());
		}
		catch(\Exception $e){
			$this->clearParams();

			throw $e;
		}

		$this->clearParams();

		return $_result;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Endpoint\EndpointInterface
	 */
	public function clearParams() : EndpointInterface{
		$this->_prepared_params = [];
		$this->_is_prepared     = false;

		return $this;
	}


	/**
	 * @return \Exception|null
	 */
	public function getException(){
		return $this->_exception;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 */
	private function prepareParams(){
		$_prepared_params = [];
		if(!empty($this->_index)){
			$_prepared_params['index'] = $this->_index;
		}
		if(!empty($this->_type)){
			$_prepared_params['type'] = $this->_type;
		}

		$_prepared_params['body'] = [];

		if(!empty($_query = $this->getQuery())){
			$_prepared_params['body']['query'] = $_query;
		}

		$this->_prepared_params = $_prepared_params;
		$this->_is_prepared     = true;
	}


}