<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 10:00
 */

namespace ElasticSearchPredicate\Predicate\Predicates;


use ElasticSearchPredicate\Predicate\AbstractPredicate;
use ElasticSearchPredicate\Predicate\PredicateException;


/**
 * Class Term
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Term extends AbstractPredicate {


	/**
	 * @var string
	 */
	protected $_term;


	/**
	 * @var bool|float|int|string
	 */
	protected $_value;


	/**
	 * Term constructor.
	 * @param string $term
	 * @param        $value
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function __construct(string $term, $value){
		$this->_term = $term;

		if(!is_scalar($value)){
			throw new PredicateException('Term value must be scalar');
		}
		$this->_value = $value;
	}


}