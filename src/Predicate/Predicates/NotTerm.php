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


/**
 * Class NotTerm
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class NotTerm extends AbstractPredicate implements BoostInterface, SimpleInterface {


	use BoostTrait, SimpleTrait;


	/**
	 * @var string
	 */
	protected $_not_term;


	/**
	 * @var bool|float|int|string
	 */
	protected $_value;


	/**
	 * NotTerm constructor.
	 * @param string                $not_term
	 * @param bool|float|int|string $value
	 * @param array                 $options
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function __construct(string $not_term, $value, array $options = []){
		$this->_not_term = $not_term;

		if(!is_scalar($value)){
			throw new PredicateException('NotTerm value must be scalar');
		}

		$this->_value = $value;

		$this->configure($options);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		$_not_term = $this->_not_term;
		if($this->_simple){
			return [
				'not' => [
					'term' => [
						$_not_term => $this->_value,
					],
				],
			];
		}

		$_ret = [
			'not' => [
				'term' => [
					$_not_term => [
						'value' => $this->_value,
					],
				],
			],
		];

		if(!empty($this->_boost)){
			$_ret['not']['term'][$_not_term]['boost'] = $this->_boost;
		}

		return $_ret;
	}
}