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
use ElasticSearchPredicate\Endpoint\Fields\FieldsInterface;
use ElasticSearchPredicate\Endpoint\Fields\FieldsTrait;
use ElasticSearchPredicate\Endpoint\Filtered\FilteredTrait;
use ElasticSearchPredicate\Endpoint\FunctionScore\FunctionScoreInterface;
use ElasticSearchPredicate\Endpoint\FunctionScore\FunctionScoreTrait;
use ElasticSearchPredicate\Endpoint\Query\QueryInterface;
use ElasticSearchPredicate\Endpoint\Query\QueryTrait;
use ElasticSearchPredicate\Predicate\FunctionScore;
use ElasticSearchPredicate\Predicate\HasChildPredicateSet;
use ElasticSearchPredicate\Predicate\HasParentPredicateSet;
use ElasticSearchPredicate\Predicate\NestedPredicateSet;
use ElasticSearchPredicate\Predicate\NotPredicateSet;
use ElasticSearchPredicate\Predicate\PredicateSet;

/**
 * Class Search
 * @package   ElasticSearchPredicate\Endpoint
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @property PredicateSet  predicate
 * @property FunctionScore function_score
 * @method PredicateSet Fuzzy(string $term, $value, array $options = [])
 * @method PredicateSet Term(string $term, $value, array $options = [])
 * @method PredicateSet Terms(string $term, array $values, array $options = [])
 * @method PredicateSet Match(string $match, $query, array $options = [])
 * @method PredicateSet Range(string $term, $from, $to = null, array $options = [])
 * @method PredicateSet QueryString($query, array $fields = [], array $options = [])
 * @method PredicateSet Exists(string $term, array $options = [])
 * @method PredicateSet Missing(string $term, array $options = [])
 * @method PredicateSet Script(array $script)
 * @method PredicateSet nest()
 * @method NotPredicateSet not()
 * @method NestedPredicateSet nested(string $path)
 * @method HasParentPredicateSet parent(string $type)
 * @method HasChildPredicateSet child(string $type)
 * @property PredicateSet  AND
 * @property PredicateSet  and
 * @property PredicateSet  OR
 * @property PredicateSet  or
 */
class Search implements EndpointInterface, QueryInterface, FunctionScoreInterface, FieldsInterface {


    use QueryTrait, FunctionScoreTrait, FilteredTrait, FieldsTrait;


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
	 * @var int
	 */
	protected $_limit;


	/**
	 * @var int
	 */
	protected $_offset;


	/**
	 * @var array
	 */
	protected $_order = [];


    /**
     * @var array
     */
    protected $_aggs = [];


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
     * @return PredicateSet
	 */
    public function __call($name, $arguments) : PredicateSet {
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
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
	 */
    public function __get($name) : PredicateSet {
		$_name = strtolower($name);
		if($_name === 'predicate'){
			return $this->getPredicate();
		}
		elseif($_name === 'function_score'){
			return $this->getFunctionScorePredicate();
		}
        elseif ($_name === 'filtered') {
            return $this->getFilteredPredicate();
        }

		return $this->getPredicate()->{$name};
	}


    /**
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
     */
    public function predicate() : PredicateSet {
        return $this->getPredicate();
    }


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 * @throws \Exception
	 */
	public function execute() : array{
		try{
			$_result = $this->_client->search($this->getPreparedParams());
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
     * @return array
     */
    public function getPreparedParams() : array {
        if (!$this->_is_prepared) {
            $this->prepareParams();
        }

        return $this->_prepared_params;
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    private function prepareParams() {
        $_prepared_params = [];
        if (!empty($this->_index)) {
            $_prepared_params['index'] = $this->_index;
        }
        if (!empty($this->_type)) {
            $_prepared_params['type'] = $this->_type;
        }
        if (is_int($this->_limit)) {
            $_prepared_params['size'] = $this->_limit;
        }
        if (is_int($this->_offset)) {
            if (empty($this->_limit)) {
                throw new EndpointException('Offset must be used with limit');
            }
            $_prepared_params['from'] = $this->_limit * $this->_offset;
        }

        $_prepared_params['body'] = [];

        if (!empty($_fields = $this->getFields())) {
            $_prepared_params['body']['stored_fields'] = $_fields;
        }
        if (!empty($_query = $this->getQuery())) {
            $_prepared_params['body']['query'] = $_query;
        }
        if (!empty($this->_order)) {
            $_prepared_params['body']['sort'] = $this->_order;
        }
        if (!empty($this->_aggs)) {
            $_prepared_params['body']['aggs'] = $this->_aggs;
        }

        $this->_prepared_params = $_prepared_params;
        $this->_is_prepared = true;
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @return \ElasticSearchPredicate\Endpoint\EndpointInterface
     */
    public function clearParams() : EndpointInterface {
        $this->_prepared_params = [];
        $this->_is_prepared = false;

        return $this;
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
	 * @param $limit
	 * @return \ElasticSearchPredicate\Endpoint\Search
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 */
	public function limit($limit) : self{
		if(!is_int($limit) && $limit !== null){
			throw new EndpointException(sprintf('Limit has wrong type %s', gettype($limit)));
		}

		$this->_limit = $limit;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @return array
	 */
    public function getOrder() : array {
		return $this->_order;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $term
	 * @param string $asc
	 * @return \ElasticSearchPredicate\Endpoint\Search
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 */
	public function order(string $term, string $asc) : self{
		$asc = strtolower($asc);
		if(!in_array($asc, [
			'asc',
			'desc',
		])
		){
			throw new EndpointException('Order type must be asc or desc');
		}

		$this->_order[] = [$term => $asc];

		return $this;
	}


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @return array
     */
    public function getAggs() : array {
        return $this->_aggs;
    }


    /**
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     * @param array $aggs
     * @return \ElasticSearchPredicate\Endpoint\Search
     */
    public function aggs(array $aggs) : self {
        $this->_aggs = $aggs;

        return $this;
    }


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Endpoint\Search
	 */
	public function resetOrder() : self{
		$this->_order = [];

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
	 * @param $offset
	 * @return \ElasticSearchPredicate\Endpoint\Search
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 */
	public function offset($offset) : self{
		if(!is_int($offset) && $offset !== null){
			throw new EndpointException(sprintf('Offset has wrong type %s', gettype($offset)));
		}
		$this->_offset = $offset;

		return $this;
	}


	/**
	 * @return \Exception|null
	 */
	public function getException(){
		return $this->_exception;
	}


}