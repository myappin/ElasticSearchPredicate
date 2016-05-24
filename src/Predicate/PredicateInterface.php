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

/**
 * Interface PredicateInterface
 * @package ElasticSearchPredicate\Predicate
 */
interface PredicateInterface {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $combiner
	 * @return \ElasticSearchPredicate\Predicate\PredicateInterface
	 */
	public function setCombiner(string $combiner) : PredicateInterface;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array;


}