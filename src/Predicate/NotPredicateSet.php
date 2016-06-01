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
	 * @param string $combiner
	 * @return \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setCombiner(string $combiner) : PredicateInterface{
		if(strtoupper($combiner) === 'or'){
			throw new PredicateException('Not allowed combiner inside not predicate');
		}

		return parent::setCombiner($combiner);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		return [
			'bool' => [
				'must_not' => $this->_predicates->map(function(PredicateInterface $predicate){
					return $predicate->toArray();
				})->values()->toArray(),
			],
		];
	}


}