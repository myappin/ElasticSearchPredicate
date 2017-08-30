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
use ElasticSearchPredicate\Predicate\Predicates\Operator\OperatorInterface;
use ElasticSearchPredicate\Predicate\Predicates\Operator\OperatorTrait;
use ElasticSearchPredicate\Predicate\Predicates\Type\TypeInterface;
use ElasticSearchPredicate\Predicate\Predicates\Type\TypeTrait;


/**
 * Class MultiMatch
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class MultiMatch extends AbstractPredicate implements TypeInterface, OperatorInterface {


	use TypeTrait, OperatorTrait;


	/**
	 * @var string
	 */
	protected $_fields;


	/**
	 * @var bool|float|int|string
	 */
	protected $_query;


	/**
	 * @var float|int
	 */
	protected $_tie_breaker;


	/**
	 * MultiMatch constructor.
	 * @param            $query
	 * @param array      $fields
	 * @param array      $options
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function __construct($query, array $fields, array $options = []){
        if (!is_scalar($query) && $query !== null) {
			throw new PredicateException('MultiMatch value must be scalar');
		}

		if(!empty($fields)){
			if(is_array($fields)){
				foreach($fields as $field){
					if(!is_scalar($field)){
						throw new PredicateException('Filed must be scalar');
					}
				}
				$this->_fields = $fields;
			}
			elseif(is_string($fields)){
				$this->_fields = [$fields];
			}
			else{
				throw new PredicateException('Unexpected field type');
			}
		}
		else{
			throw new PredicateException('Fields can not be empty set');
		}

		$this->_query = $query;

		$this->_other_options = ['tie_breaker'];
		$this->_types         = [
			'phrase',
			'phrase_prefix',
			'cross_fields',
			'most_fields',
			'best_fields',
		];

		$this->configure($options);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $tie_breaker
	 * @return \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function tie_breaker($tie_breaker) : PredicateInterface{
        if (!is_int($tie_breaker) && !is_float($tie_breaker)) {
			throw new PredicateException('Tie breaker must be int or float');
		}
		if($tie_breaker < 0 || $tie_breaker > 1){
			throw new PredicateException('Tie breaker must be between 0 and 1');
		}
		$this->_tie_breaker = $tie_breaker;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		$_ret = [
			'multi_match' => [
				'query' => $this->_query,
			],
		];

		if(!empty($this->_fields)){
			$_ret['multi_match']['fields'] = $this->_fields;
		}

		if(!empty($this->_type)){
			$_ret['multi_match']['type'] = $this->_type;
		}

		if(!empty($this->_operator)){
			$_ret['multi_match']['operator'] = $this->_operator;
		}

		if(!empty($this->_tie_breaker)){
			$_ret['multi_match']['tie_breaker'] = $this->_tie_breaker;
		}


		return $_ret;
	}
}