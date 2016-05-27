<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 10:00
 */

namespace ElasticSearchPredicate\Predicate\Predicates;


use ElasticSearchPredicate\Predicate\PredicateException;
use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostInterface;
use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostTrait;


/**
 * Class QueryString
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class QueryString extends AbstractPredicate implements BoostInterface {


	use BoostTrait;


	/**
	 * @var bool|float|int|string
	 */
	protected $_query;


	/**
	 * @var array
	 */
	protected $_fields = [];


	/**
	 * QueryString constructor.
	 * @param bool|float|int|string $query
	 * @param array                 $fields
	 * @param array                 $options
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function __construct($query, array $fields = [], array $options = []){
		if(!is_scalar($query)){
			throw new PredicateException('Query must be scalar');
		}

		$this->_query = $query;

		if(!empty($fields)){
			foreach($fields as $field){
				if(!is_scalar($field)){
					throw new PredicateException('Filed must be scalar');
				}
			}
			$this->_fields = $fields;
		}

		$this->configure($options);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		$_ret = [
			'query_string' => [
				'query' => $this->_query,
			],
		];

		if(!empty($this->_fields)){
			$_ret['query_string']['fields'] = $this->_fields;
		}

		if(!empty($this->_boost)){
			$_ret['query_string']['boost'] = $this->_boost;
		}

		return $_ret;
	}
}