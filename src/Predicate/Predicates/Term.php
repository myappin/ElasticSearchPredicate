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
use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostInterface;
use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostTrait;
use ElasticSearchPredicate\Predicate\Predicates\Simple\SimpleInterface;
use ElasticSearchPredicate\Predicate\Predicates\Simple\SimpleTrait;


/**
 * Class Term
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Term extends AbstractPredicate implements BoostInterface, SimpleInterface {


	use BoostTrait, SimpleTrait;


	/**
	 * @var string
	 */
	protected $_term;


	/**
	 * @var bool|float|int|string
	 */
	protected $_value;


	/**
	 * Term constructor.
	 * @param string     $term
	 * @param            $value
	 * @param array      $options
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function __construct(string $term, $value, array $options = []){
		$this->_term = $term;

		if(!is_scalar($value)){
			throw new PredicateException('Term value must be scalar');
		}

		$this->_value = $value;

		$this->configure($options);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		$_term = $this->_term;
		if($this->_simple){
			return [
				'term' => [
					$_term => $this->_value,
				],
			];
		}

		$_ret = [
			'term' => [
				$_term => [
					'value' => $this->_value,
				],
			],
		];

		if(!empty($this->_boost)){
			$_ret['term'][$_term]['boost'] = $this->_boost;
		}

		return $_ret;
	}
}