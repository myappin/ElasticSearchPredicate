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

namespace ElasticSearchPredicate\Predicate\ScoreFunction\Weight;


use ElasticSearchPredicate\Endpoint\EndpointException;


/**
 * Class WeightTrait
 * @package   ElasticSearchPredicate\Predicate\ScoreFunction\Weigh
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait WeightTrait {


	/**
	 * @var int|float
	 */
	protected $_weight;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $weight
	 * @return $this
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 */
	public function setWeight($weight){
		if(!is_int($weight) && !is_float($weight)){
			throw new EndpointException('Weight sould be int of float');
		}
		$this->_weight = $weight;

		return $this;
	}


}