<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 9:55
 */

namespace ElasticSearchPredicate\Predicate;


use DusanKasan\Knapsack\Collection;
use ElasticSearchPredicate\Predicate\Predicates\PredicateInterface;


/**
 * Class PredicateSet
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @method PredicateSet Term(string $term, $value, array $options = [])
 * @method PredicateSet Match(string $match, $query, array $options = [])
 * @method PredicateSet Range(string $term, $from, $to, array $options = [])
 * @method PredicateSet QueryString($query, array $fields = [], array $options = [])
 * @property PredicateSet AND
 * @property PredicateSet and
 * @property PredicateSet OR
 * @property PredicateSet or
 */
class PredicateSet implements PredicateSetInterface {


	const C_AND = 'AND';


	const C_OR = 'OR';


	/**
	 * @var bool
	 */
	protected $_unnest = false;


	/**
	 * @var string
	 */
	protected $_combiner = self::C_AND;


	/**
	 * @var Collection
	 */
	protected $_predicates;


	/**
	 * PredicateSet constructor.
	 * @param \ElasticSearchPredicate\Predicate\PredicateSetInterface|null $unnest
	 */
	public function __construct(PredicateSetInterface $unnest = null){
		$this->_unnest     = $unnest;
		$this->_predicates = new Collection([]);
	}


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
			if($this->_predicates->isNotEmpty()){
				$this->_predicates->last()->setCombiner($this->_combiner);
			}
			/** @var PredicateInterface $_predicate */
			$_predicate        = new $_class;
			$this->_last       = $_predicate;
			$this->_predicates = $this->_predicates->append($_predicate);
		}
		else{
			if($this->_predicates->isNotEmpty()){
				$this->_predicates->last()->setCombiner($this->_combiner);
			}
			/** @var PredicateInterface $_predicate */
			$_predicate        = (new \ReflectionClass($_class))->newInstanceArgs($arguments);
			$this->_last       = $_predicate;
			$this->_predicates = $this->_predicates->append($_predicate);
		}

		$this->_combiner = self::C_AND;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $name
	 * @return $this|null
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function __get($name){
		if(in_array($_combiner = strtoupper($name), [
			self::C_AND,
			self::C_OR,
		])){
			$this->setCombiner($_combiner);

			return $this;
		}

		throw new PredicateException(sprintf('Property %s does not exist', $name));
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface $predicate
	 * @return \ElasticSearchPredicate\Predicate\PredicateSetInterface
	 */
	public function addPredicate(PredicateInterface $predicate) : PredicateSetInterface{
		if($this->_predicates->isNotEmpty()){
			$this->_predicates->last()->setCombiner($this->_combiner);
		}
		$this->_last       = $predicate;
		$this->_predicates = $this->_predicates->append($predicate);

		$this->_combiner = self::C_AND;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface $predicate
	 * @return \ElasticSearchPredicate\Predicate\PredicateSetInterface
	 */
	public function andPredicate(PredicateInterface $predicate) : PredicateSetInterface{
		$this->_combiner = self::C_AND;

		return $this->addPredicate($predicate);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface $predicate
	 * @return \ElasticSearchPredicate\Predicate\PredicateSetInterface
	 */
	public function orPredicate(PredicateInterface $predicate) : PredicateSetInterface{
		$this->_combiner = self::C_OR;

		return $this->addPredicate($predicate);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface $predicate
	 * @return \ElasticSearchPredicate\Predicate\PredicateSetInterface
	 */
	public function and (PredicateInterface $predicate) : PredicateSetInterface{
		return $this->andPredicate($predicate);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface $predicate
	 * @return \ElasticSearchPredicate\Predicate\PredicateSetInterface
	 */
	public function or (PredicateInterface $predicate) : PredicateSetInterface{
		return $this->andPredicate($predicate);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Predicate\PredicateSet
	 */
	public function nest() : PredicateSet{
		if($this->_predicates->isNotEmpty()){
			$this->_predicates->last()->setCombiner($this->_combiner);
		}
		$_nest             = new PredicateSet($this);
		$this->_last       = $_nest;
		$this->_predicates = $this->_predicates->append($_nest);

		$this->_combiner = self::C_AND;

		return $_nest;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * nested mappings
	 * @return \ElasticSearchPredicate\Predicate\PredicateSet
	 */
	public function nested(string $path) : PredicateSet{
		if($this->_predicates->isNotEmpty()){
			$this->_predicates->last()->setCombiner($this->_combiner);
		}
		$_nest = new Nested($this);
		$_nest->setPath($path);
		$this->_last       = $_nest;
		$this->_predicates = $this->_predicates->append($_nest);

		$this->_combiner = self::C_AND;

		return $_nest;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \ElasticSearchPredicate\Predicate\PredicateSet
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function unnest() : PredicateSet{
		if(empty($this->_unnest)){
			throw new PredicateException('Can not unnest not nested predicate');
		}
		$_unnest       = $this->_unnest;
		$this->_unnest = false;

		return $_unnest;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return Collection
	 */
	public function getPredicates() : Collection{
		return $this->_predicates;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return string
	 */
	public function getCombiner() : string{
		return $this->_combiner;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $combiner
	 * @return \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setCombiner(string $combiner) : PredicateInterface{
		$combiner = strtoupper($combiner);
		if($combiner !== PredicateSet::C_AND && $combiner !== PredicateSet::C_OR){
			throw new PredicateException('Unsupported combiner');
		}
		$this->_combiner = $combiner;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 */
	public function toArray() : array{
		$_predicates = $this->_predicates;
		$_size       = $_predicates->size();
		if($_size < 1){
			return [];
		}
		if($_size === 1){
			return $_predicates->first()->toArray();
		}
		elseif($_size === 2){
			if($_predicates->first()->getCombiner() === PredicateSet::C_AND){
				return [
					'bool' => [
						'must' => $_predicates->map(function(PredicateInterface $predicate){
							return $predicate->toArray();
						})->values()->toArray(),
					],
				];
			}
			else{
				return [
					'bool' => [
						'should' => $_predicates->map(function(PredicateInterface $predicate){
							return $predicate->toArray();
						})->values()->toArray(),
					],
				];
			}
		}
		else{
			$_combiner   = null;
			$_index      = 0;
			$_partitions = $_predicates->partitionBy(function(PredicateInterface $predicate) use (&$_combiner, &$_index){
				if($_combiner === PredicateSet::C_OR){
					$_index++;
				}
				$_combiner = $predicate->getCombiner();

				return $_index;
			});
			if($_partitions->sizeIs(1)){
				return [
					'bool' => $_partitions->map(function(Collection $partition){
						if($partition->first()->getCombiner() === PredicateSet::C_AND){
							return [
								'must' => $partition->map(function(PredicateInterface $predicate){
									return $predicate->toArray();
								})->values()->toArray(),
							];
						}
						else{
							return [
								'should' => $partition->map(function(PredicateInterface $predicate){
									return $predicate->toArray();
								})->values()->toArray(),
							];
						}
					})->current(),
				];
			}
			else{
				return [
					'bool' => [
						'should' => $_partitions->map(function(Collection $partition){
							if($partition->sizeIs(1)){
								return $partition->first()->toArray();
							}
							else{
								if($partition->first()->getCombiner() === PredicateSet::C_AND){
									return [
										'bool' => [
											'must' => $partition->map(function(PredicateInterface $predicate){
												return $predicate->toArray();
											})->values()->toArray(),
										],
									];
								}
								else{
									return [
										'bool' => [
											'should' => $partition->map(function(PredicateInterface $predicate){
												return $predicate->toArray();
											})->values()->toArray(),
										],
									];
								}
							}
						})->values()->toArray(),
					],
				];
			}
		}
	}


}
