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

namespace ElasticSearchPredicate\Predicate;


use Elasticsearch\Client;

/**
 * Class SearchPredicate
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class SearchPredicate {


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
	public function execute(){
		$_result = $this->_client->search($this->getPreparedParams());

		$this->reset();

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
	public function reset(){
		$this->_prepared_params = [];
		$this->_is_prepared     = false;
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
				throw new PredicateException('Offset must be used with limit');
			}
			$_prepared_params['from'] = $this->_limit * $this->_offset;
		}

		$_prepared_params['body'] = [];

		$this->_prepared_params = $_prepared_params;
		$this->_is_prepared     = true;
	}


}