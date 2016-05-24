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
use ElasticSearchPredicate\Predicate\PredicateSet;

/**
 * Class Search
 * @package   ElasticSearchPredicate\Endpoint
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @property PredicateSet predicate
 */
class Search implements EndpointInterface, QueryInterface {


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


	/** @var  int */
	protected $_limit;


	/** @var  int */
	protected $_offset;


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
	 * @return null
	 */
	public function __get($name){
		if(strtolower($name) === 'predicate'){
			return $this->getPredicate();
		}

		return null;
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
	 */
	public function execute() : array{
		$_result = $this->_client->search($this->getPreparedParams());

		$this->clearParams();

		return $_result;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return int|null
	 */
	public function getLimit(){
		return $this->_limit;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param int|null $limit
	 * @return $this
	 */
	public function setLimit($limit){
		$this->_limit = $limit;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return int|null
	 */
	public function getOffset(){
		return $this->_offset;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param int|null $offset
	 * @return $this
	 */
	public function setOffset($offset){
		$this->_offset = $offset;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 */
	public function clearParams() : EndpointInterface{
		$this->_prepared_params = [];
		$this->_is_prepared     = false;

		return $this;
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
		if(!empty($this->_limit)){
			$_prepared_params['size'] = $this->_limit;
		}
		if(!empty($this->_offset)){
			if(empty($this->_limit)){
				throw new EndpointException('Offset must be used with limit');
			}
			$_prepared_params['from'] = $this->_limit * $this->_offset;
		}

		$_prepared_params['body'] = [];

		if(!empty($_query = $this->getQuery())){
			$_prepared_params['query'] = $_query;
		}

		$this->_prepared_params = $_prepared_params;
		$this->_is_prepared     = true;
	}


}