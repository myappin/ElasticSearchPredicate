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
 * Class Range
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Range extends AbstractPredicate implements BoostInterface {


	use BoostTrait;


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
	 * @var string
	 */
	protected $_from_type = 'gte';


	/**
	 * @var string
	 */
	protected $_to_type = 'lte';


	/**
	 * @var string
	 */
	protected $_format;


	/**
	 * @var array
	 */
	protected $_other_options = ['types'];


	/**
	 * Range constructor.
	 * @param string $term
	 * @param        $from
	 * @param        $to
	 * @param array  $options
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function __construct(string $term, $from, $to = null, array $options = []){
		$this->_term = $term;

		if($from === null && $to === null){
			throw new PredicateException('Both of from and to can not be null');
		}

		$this->_from = $from;
		$this->_to   = $to;

		$this->configure($options);
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
	 * @param string|null $from
	 * @param null|string $to
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function types($from, $to = null){
		if($from !== null){
			if(!in_array($from, [
				'gte',
				'gt',
			], true)
			){
				throw new PredicateException('From type can be one of gt and gte');
			}
			$this->_from_type = $from;
		}
		if($to !== null){
			if(!in_array($to, [
				'lte',
				'lt',
			], true)
			){
				throw new PredicateException('From type can be one of gt and gte');
			}
			$this->_to_type = $to;
		}
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		$_term = $this->_term;
		$_ret  = [
			'range' => [
				$_term => [],
			],
		];

		if($this->_from !== null){
			$_ret['range'][$_term][$this->_from_type] = $this->_from;
		}

		if($this->_to !== null){
			$_ret['range'][$_term][$this->_to_type] = $this->_to;
		}

		if(!empty($this->_boost)){
			$_ret['range'][$_term]['boost'] = $this->_boost;
		}

		if(!empty($this->_format)){
			$_ret['range'][$_term]['format'] = $this->_format;
		}

		return $_ret;
	}
}
