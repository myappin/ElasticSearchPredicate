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
use ElasticSearchPredicate\Predicate\PredicateInterface;
use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostInterface;
use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostTrait;
use ElasticSearchPredicate\Predicate\Predicates\Simple\SimpleInterface;
use ElasticSearchPredicate\Predicate\Predicates\Simple\SimpleTrait;
use ElasticSearchPredicate\Predicate\Predicates\Type\TypeInterface;


/**
 * Class Match
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Match extends AbstractPredicate implements BoostInterface, SimpleInterface, TypeInterface {


	use BoostTrait, SimpleTrait;


	/**
	 * @var string
	 */
	protected $_match;


	/**
	 * @var bool|float|int|string
	 */
	protected $_value;


	/**
	 * @var string
	 */
	protected $_type;


	/**
	 * Match constructor.
	 * @param string     $match
	 * @param            $query
	 * @param array      $options
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function __construct(string $match, $query, array $options = []){
		$this->_match = $match;

		if(!is_scalar($query)){
			throw new PredicateException('Match value must be scalar');
		}

		$this->_value = $query;

		$this->configure($options);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $type
	 * @return \ElasticSearchPredicate\Predicate\PredicateInterface
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
		$_match = $this->_match;
		if($this->_simple){
			return [
				'match' => [
					$_match => $this->_value,
				],
			];
		}

		$_ret = [
			'match' => [
				$_match => [
					'query' => $this->_value,
				],
			],
		];

		if(!empty($this->_boost)){
			$_ret['match'][$_match]['boost'] = $this->_boost;
		}

		if(!empty($this->_type)){
			$_ret['match'][$_match]['type'] = $this->_type;
		}

		return $_ret;
	}
}