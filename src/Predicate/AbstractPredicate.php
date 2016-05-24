<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 9:36
 */

namespace ElasticSearchPredicate\Predicate;


use DusanKasan\Knapsack\Collection;
use ElasticSearchPredicate\Predicate\Predicates\Term;


/**
 * Class AbstractPredicate
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @method Term term
 */
class AbstractPredicate implements PredicateInterface {


	const C_AND = 'AND';


	const C_OR = 'OR';


	/**
	 * @var bool
	 */
	protected $_unnest = false;


	/**
	 * @var array
	 */
	protected $_predicates = [];


	/**
	 * @var string
	 */
	protected $_combiner = self::C_AND;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $name
	 * @param $arguments
	 * @return $this
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function __call($name, $arguments){
		$name   = preg_replace('/[^a-z0-9\_]+/i', '', $name);
		$_class = 'ElasticSearchPredicate\Predicate\Predicates\\' . $name;
		if(!class_exists($_class)){
			throw new PredicateException(sprintf('Predicate %s does not exist', $name));
		}
		if(empty($arguments)){
			$this->_predicates[] = new $_class;
		}
		else{
			$this->_predicates[] = (new \ReflectionClass($_class))->newInstanceArgs($arguments);
		}

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param \ElasticSearchPredicate\Predicate\PredicateInterface $predicate
	 * @return \ElasticSearchPredicate\Predicate\PredicateInterface
	 */
	public function andPredicate(PredicateInterface $predicate) : PredicateInterface{
		$predicate->setCombiner(self::C_AND);
		$this->_predicates[] = $predicate;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param \ElasticSearchPredicate\Predicate\PredicateInterface $predicate
	 * @return \ElasticSearchPredicate\Predicate\PredicateInterface
	 */
	public function orPredicate(PredicateInterface $predicate) : PredicateInterface{
		$predicate->setCombiner(self::C_OR);
		$this->_predicates[] = $predicate;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Predicate\PredicateInterface
	 */
	public function nest() : PredicateInterface{
		return new PredicateSet($this);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Predicate\PredicateInterface
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function unnest() : PredicateInterface{
		if(empty($this->_unnest)){
			throw new PredicateException('Can not unnest not nested predicate');
		}
		$_unnest       = $this->_unnest;
		$this->_unnest = false;

		return $_unnest;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $combiner
	 * @return \ElasticSearchPredicate\Predicate\PredicateInterface
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setCombiner(string $combiner) : PredicateInterface{
		if($combiner !== self::C_AND && $combiner !== self::C_OR){
			throw new PredicateException('Unsupported combiner');
		}
		$this->_combiner = $combiner;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return Collection
	 */
	public function getPredicates() : Collection{
		return new Collection($this->_predicates);
	}
}