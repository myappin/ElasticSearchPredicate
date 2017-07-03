<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 30. 5. 2016
 * Time: 15:08
 */

namespace ElasticSearchPredicate\Predicate\PredicateSet;

use ElasticSearchPredicate\Predicate\PredicateSet;


/**
 * Class PredicateSetTrait
 * @package   ElasticSearchPredicate\Predicate\PredicateSet
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait PredicateSetTrait {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $term
	 * @param        $value
	 * @param array  $options
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
	 */
    public function equalTo(string $term, $value, array $options = []) : PredicateSet {
		return $this->Term($term, $value, $options);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $term
	 * @param        $from
	 * @param        $to
	 * @param array  $options
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
	 */
    public function between(string $term, $from, $to, array $options = []) : PredicateSet {
		return $this->Range($term, $from, $to, $options);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $term
	 * @param        $from
	 * @param array  $options
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
	 */
    public function greaterThan(string $term, $from, array $options = []) : PredicateSet {
		return $this->Range($term, $from, null, $options);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $term
	 * @param        $to
	 * @param array  $options
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
	 */
    public function lessThan(string $term, $to, array $options = []) : PredicateSet {
		return $this->Range($term, null, $to, $options);
	}


}