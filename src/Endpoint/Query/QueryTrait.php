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

namespace ElasticSearchPredicate\Endpoint\Query;


use ElasticSearchPredicate\Predicate\PredicateSet;


/**
 * Class QueryTrait
 * @package   ElasticSearchPredicate\Endpoint\Query
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait QueryTrait {


	/** @var PredicateSet */
	protected $_predicate;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function getQuery() : array{
		return $this->getPredicate()->toArray();
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Predicate\PredicateSet
	 */
	public function getPredicate(){
		if(!$this->_predicate instanceof PredicateSet){
			$this->_predicate = new PredicateSet();
		}

		return $this->_predicate;
	}


}