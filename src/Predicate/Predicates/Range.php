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


use ElasticSearchPredicate\Predicate\AbstractPredicate;
use ElasticSearchPredicate\Predicate\PredicateException;


/**
 * Class Range
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Range extends AbstractPredicate {


	/**
	 * @var string
	 */
	protected $_term;


	/**
	 * @var int|float
	 */
	protected $_from;


	/**
	 * @var int|float
	 */
	protected $_to;


	/**
	 * @var
	 */
	protected $_boost;


	/**
	 * @var array
	 */
	protected $_allowed_options = ['boost'];


	/**
	 * Range constructor.
	 * @param string $term
	 * @param        $from
	 * @param        $to
	 * @param array  $options
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function __construct(string $term, $from, $to, array $options = []){
		$this->_term = $term;

		if(!is_int($from) && !is_float($from)){
			throw new PredicateException('Range from must be scalar');
		}

		if(!is_int($to) && !is_float($to)){
			throw new PredicateException('Range to must be scalar');
		}

		if($to < $from){
			throw new PredicateException('Value to must be greater than value to');
		}

		$this->_from = $from;
		$this->_to   = $to;

		$this->configure($options);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param int $boost
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function boost(int $boost){
		if($boost < 0){
			throw new PredicateException('Boost must be greater than 0');
		}
		$this->_boost = $boost;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		$_term = $this->_term;
		$_ret  = [
			'range' => [
				$_term => [
					'gte' => $this->_from,
					'lte' => $this->_to,
				],
			],
		];

		if(!empty($this->_boost)){
			$_ret['term'][$_term]['boost'] = $this->_boost;
		}

		return $_ret;
	}
}