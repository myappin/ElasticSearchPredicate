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
use ElasticSearchPredicate\Predicate\PredicateSet;


/**
 * Class FunctionScoreTrait
 * @package   ElasticSearchPredicate\Endpoint\FunctionScore
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait FunctionScoreTrait {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Predicate\FunctionScore
	 */
	public function getFunctionScorePredicate() : FunctionScore{
		if(!$this->_predicate instanceof FunctionScore){
			if($this->_predicate instanceof PredicateSet){
				$_score_function = new FunctionScore();
				$_score_function->setCombiner($this->_predicate->getCombiner());
				$_score_function->setPredicates($this->_predicate->getPredicates());

				return $this->_predicate = $_score_function;
			}
			else{
				return $this->_predicate = new FunctionScore();
			}
		}

		return $this->_predicate;
	}


}