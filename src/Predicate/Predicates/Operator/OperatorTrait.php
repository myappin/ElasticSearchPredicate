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

namespace ElasticSearchPredicate\Predicate\Predicates\Operator;


use ElasticSearchPredicate\Predicate\PredicateException;
use ElasticSearchPredicate\Predicate\Predicates\PredicateInterface;


/**
 * Class OperatorTrait
 * @package   ElasticSearchPredicate\Predicate\Predicates\Operator
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait OperatorTrait {


	/**
	 * @var string
	 */
	protected $_operator;


	/**
	 * @var array
	 */
	protected $_operators = [
		'and',
		'or',
	];


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $operator
	 * @return \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function operator(string $operator) : PredicateInterface{
		if(!in_array($operator, $this->_operators, true)){
			throw new PredicateException('Operator is not valid');
		}

		$this->_operator = $operator;
		$this->_simple   = false;

		return $this;
	}


}
