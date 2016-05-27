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
use ElasticSearchPredicate\Predicate\Predicates\Simple\SimpleInterface;
use ElasticSearchPredicate\Predicate\Predicates\Simple\SimpleTrait;
use ElasticSearchPredicate\Predicate\Predicates\Type\TypeInterface;


/**
 * Class NotMatch
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class NotMatch extends AbstractPredicate implements BoostInterface, SimpleInterface, TypeInterface {


	use BoostTrait, SimpleTrait;


	/**
	 * @var string
	 */
	protected $_not_match;


	/**
	 * @var bool|float|int|string
	 */
	protected $_value;


	/**
	 * @var string
	 */
	protected $_type;


	/**
	 * NotMatch constructor.
	 * @param string     $not_match
	 * @param            $query
	 * @param array      $options
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function __construct(string $not_match, $query, array $options = []){
		$this->_not_match = $not_match;

		if(!is_scalar($query)){
			throw new PredicateException('NotMatch value must be scalar');
		}

		$this->_value = $query;

		$this->configure($options);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $type
	 * @return \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function type(string $type) : PredicateInterface{
		if(!in_array($type, [
			'phrase',
			'phrase_prefix',
		])
		){
			throw new PredicateException('Type is not valid');
		}

		$this->_type   = $type;
		$this->_simple = false;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		$_not_match = $this->_not_match;
		if($this->_simple){
			return [
				'not' => [
					'match' => [
						$_not_match => $this->_value,
					],
				],
			];
		}

		$_ret = [
			'not' => [
				'match' => [
					$_not_match => [
						'query' => $this->_value,
					],
				],
			],
		];

		if(!empty($this->_boost)){
			$_ret['not']['match'][$_not_match]['boost'] = $this->_boost;
		}

		if(!empty($this->_type)){
			$_ret['not']['match'][$_not_match]['type'] = $this->_type;
		}

		return $_ret;
	}
}