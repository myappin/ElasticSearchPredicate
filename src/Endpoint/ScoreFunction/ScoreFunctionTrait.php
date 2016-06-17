<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 13:19
 */

namespace ElasticSearchPredicate\Endpoint\ScoreFunction;


use ElasticSearchPredicate\Predicate\ScoreFunction;


/**
 * Class ScoreFunctionTrait
 * @package   ElasticSearchPredicate\Endpoint\ScoreFunction
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait ScoreFunctionTrait {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Predicate\PredicateSet
	 */
	public function getScoreFunctionPredicate() : ScoreFunction{
		if(!$this->_predicate instanceof ScoreFunction){
			$this->_predicate = new ScoreFunction();
		}

		return $this->_predicate;
	}


}