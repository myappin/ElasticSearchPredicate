<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 9:41
 */

namespace ElasticSearchPredicate\Predicate;


use DusanKasan\Knapsack\Collection;

/**
 * Interface PredicateSetInterface
 * @package ElasticSearchPredicate\Predicate
 */
interface PredicateSetInterface extends PredicateInterface {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param \ElasticSearchPredicate\Predicate\PredicateInterface $predicate
	 * @return \ElasticSearchPredicate\Predicate\PredicateSetInterface
	 */
	public function addPredicate(PredicateInterface $predicate) : PredicateSetInterface;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param \ElasticSearchPredicate\Predicate\PredicateInterface $predicate
	 * @return \ElasticSearchPredicate\Predicate\PredicateSetInterface
	 */
	public function andPredicate(PredicateInterface $predicate) : PredicateSetInterface;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param \ElasticSearchPredicate\Predicate\PredicateInterface $predicate
	 * @return \ElasticSearchPredicate\Predicate\PredicateSetInterface
	 */
	public function orPredicate(PredicateInterface $predicate) : PredicateSetInterface;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Predicate\PredicateSetInterface
	 */
	public function nest() : PredicateSetInterface;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Predicate\PredicateSetInterface
	 */
	public function unnest() : PredicateSetInterface;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return Collection
	 */
	public function getPredicates() : Collection;


}