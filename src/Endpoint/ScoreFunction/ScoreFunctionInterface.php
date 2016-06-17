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

namespace ElasticSearchPredicate\Endpoint\ScoreFunction;


use ElasticSearchPredicate\Predicate\ScoreFunction;


/**
 * Interface ScoreFunctionInterface
 * @package ElasticSearchPredicate\Endpoint\ScoreFunction
 */
interface ScoreFunctionInterface {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Predicate\ScoreFunction
	 */
	public function getScoreFunctionPredicate() : ScoreFunction;


}