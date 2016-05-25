<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 25. 5. 2016
 * Time: 22:42
 */

namespace ElasticSearchPredicate\Predicate\Predicates\Boost;


/**
 * Interface BoostInterface
 * @package ElasticSearchPredicate\Predicate\Predicates\Boost
 */
interface BoostInterface {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $boost
	 * @return $this
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function boost($boost);


}