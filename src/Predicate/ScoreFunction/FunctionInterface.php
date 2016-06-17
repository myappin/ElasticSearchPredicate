<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 17. 6. 2016
 * Time: 9:58
 */

namespace ElasticSearchPredicate\Predicate\ScoreFunction;


/**
 * Interface FunctionInterface
 * @package   ElasticSearchPredicate\Predicate\ScoreFunction
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
interface FunctionInterface {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array;


}