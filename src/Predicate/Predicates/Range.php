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
	 * @var int|float
	 */
	protected $_boost;


	/**
	 * @var string
	 */
	protected $_format;


	/**
	 * @var array
	 */
	protected $_allowed_options = [
		'boost',
		'format',
	];


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

		$this->_from = $from;
		$this->_to   = $to;

		$this->configure($options);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $boost
	 * @return $this
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function boost($boost){
		if(is_int($boost) || is_float($boost)){
			throw new PredicateException('Boost must be int or float');
		}
		if($boost < 0){
			throw new PredicateException('Boost must be greater than 0');
		}
		$this->_boost  = $boost;
		$this->_simple = false;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $format
	 * @return $this
	 */
	public function format(string $format){
		$this->_format = $format;
		$this->_simple = false;

		return $this;
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

		if(!empty($this->_format)){
			$_ret['term'][$_term]['format'] = $this->_format;
		}

		return $_ret;
	}
}