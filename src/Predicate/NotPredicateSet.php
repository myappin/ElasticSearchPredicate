<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 9:55
 */

namespace ElasticSearchPredicate\Predicate;


use ElasticSearchPredicate\Predicate\Predicates\PredicateInterface;


/**
 * Class NotPredicateSet
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class NotPredicateSet extends PredicateSet {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		return [
			'bool' => [
				'must_not' => $this->_predicates->map(function(PredicateInterface $predicate){
					if($predicate instanceof PredicateSetInterface){
						throw new PredicateException('Only end predicates are allowed in NotPredicateSet');
					}

					return $predicate->toArray();
				})->values()->toArray(),
			],
		];
	}


}