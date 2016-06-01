<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 25. 5. 2016
 * Time: 22:47
 */

namespace ElasticSearchPredicate\Predicate\Predicates\Operator;


use ElasticSearchPredicate\Predicate\Predicates\PredicateInterface;


/**
 * Interface OperatorInterface
 * @package ElasticSearchPredicate\Predicate\Predicates\Operator
 */
interface OperatorInterface {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $operator
	 * @return mixed
	 */
	public function operator(string $operator) : PredicateInterface;


}