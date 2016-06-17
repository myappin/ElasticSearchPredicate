<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 10:28
 */

namespace ElasticSearchPredicate\Endpoint\FunctionScore;


use ElasticSearchPredicate\Predicate\FunctionScore;


/**
 * Interface FunctionScoreInterface
 * @package ElasticSearchPredicate\Endpoint\FunctionScore
 */
interface FunctionScoreInterface {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Predicate\FunctionScore
	 */
	public function getFunctionScorePredicate() : FunctionScore;


}