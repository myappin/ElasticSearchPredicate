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

namespace ElasticSearchPredicate\Predicate\Predicates\Type;


use ElasticSearchPredicate\Predicate\PredicateException;
use ElasticSearchPredicate\Predicate\Predicates\PredicateInterface;


/**
 * Class TypeTrait
 * @package   ElasticSearchPredicate\Predicate\Predicates\Type
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait TypeTrait {


	/**
	 * @var string
	 */
	protected $_type;


	/**
	 * @var array
	 */
	protected $_types = [];


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $type
	 * @return \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function type(string $type) : PredicateInterface{
		if(!in_array($type, $this->_types, true)){
			throw new PredicateException(sprintf('Type %s is not valid', $type));
		}

		$this->_type   = $type;
		$this->_simple = false;

		return $this;
	}


}
