<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 17. 6. 2016
 * Time: 10:52
 */

namespace ElasticSearchPredicate\Predicate\FunctionScore;


use ElasticSearchPredicate\Endpoint\Query\QueryTrait;
use ElasticSearchPredicate\Predicate\FunctionScore\Weight\WeightTrait;
use ElasticSearchPredicate\Predicate\PredicateSet;


/**
 * Class AbstractFunction
 * @package   ElasticSearchPredicate\Predicate\FunctionScore
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @property PredicateSet predicate
 * @property PredicateSet AND
 * @property PredicateSet and
 * @property PredicateSet OR
 * @property PredicateSet or
 */
abstract class AbstractFunction implements FunctionInterface {


	use QueryTrait, WeightTrait;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $name
	 * @param $arguments
     * @return PredicateSet
	 */
    public function __call($name, $arguments) : PredicateSet {
		if(empty($arguments)){
			return call_user_func([
									  $this->getPredicate(),
									  $name,
								  ]);
		}
		else{
			return call_user_func_array([
											$this->getPredicate(),
											$name,
										], $arguments);
		}
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $name
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
	 */
    public function __get($name) : PredicateSet {
		$_name = strtolower($name);
		if($_name === 'predicate'){
			return $this->getPredicate();
		}

		return $this->getPredicate()->{$name};
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	abstract public function toArray() : array;


}