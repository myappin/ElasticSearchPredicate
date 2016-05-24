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


class PredicateSet extends AbstractPredicate {


	/**
	 * PredicateSet constructor.
	 * @param \ElasticSearchPredicate\Predicate\PredicateInterface|null $unnest
	 */
	public function __construct(PredicateInterface $unnest = null){
		$this->_unnest = $unnest;
	}


}