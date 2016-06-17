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

namespace ElasticSearchPredicate\Endpoint\FunctionScore;


use ElasticSearchPredicate\Predicate\FunctionScore;


/**
 * Class FunctionScoreTrait
 * @package   ElasticSearchPredicate\Endpoint\FunctionScore
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait FunctionScoreTrait {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Predicate\PredicateSet
	 */
	public function getFunctionScorePredicate() : FunctionScore{
		if(!$this->_predicate instanceof FunctionScore){
			$this->_predicate = new FunctionScore();
		}

		return $this->_predicate;
	}


}