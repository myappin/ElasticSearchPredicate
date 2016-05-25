<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 25. 5. 2016
 * Time: 22:36
 */

namespace ElasticSearchPredicate\Predicate\Predicates\Boost;


use ElasticSearchPredicate\Predicate\PredicateException;

/**
 * Class BoostTrait
 * @package   ElasticSearchPredicate\Predicate\Predicates\Boost
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait BoostTrait {


	/**
	 * @var int
	 */
	protected $_boost;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $boost
	 * @return $this
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function boost($boost){
		if(!is_int($boost) && !is_float($boost)){
			throw new PredicateException('Boost must be int or float');
		}
		if($boost < 0){
			throw new PredicateException('Boost must be greater than 0');
		}
		$this->_boost  = $boost;
		$this->_simple = false;

		return $this;
	}


}