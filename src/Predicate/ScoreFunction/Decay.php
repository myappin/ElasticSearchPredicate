<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 17. 6. 2016
 * Time: 10:51
 */

namespace ElasticSearchPredicate\Predicate\ScoreFunction;


use DusanKasan\Knapsack\Collection;
use ElasticSearchPredicate\Endpoint\Query\QueryInterface;
use ElasticSearchPredicate\Endpoint\Query\QueryTrait;
use ElasticSearchPredicate\Predicate\PredicateException;
use ElasticSearchPredicate\Predicate\PredicateSet;
use ElasticSearchPredicate\Predicate\PredicateSetInterface;
use ElasticSearchPredicate\Predicate\ScoreFunction\Field\FieldInterface;
use ElasticSearchPredicate\Predicate\ScoreFunction\Weight\WeightInterface;
use ElasticSearchPredicate\Predicate\ScoreFunction\Weight\WeightTrait;


/**
 * Class Decay
 * @package   ElasticSearchPredicate\Predicate\ScoreFunction
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @property PredicateSet predicate
 * @property PredicateSet AND
 * @property PredicateSet and
 * @property PredicateSet OR
 * @property PredicateSet or
 */
class Decay extends AbstractFunction implements QueryInterface, WeightInterface {


	use QueryTrait, WeightTrait;


	/**
	 * @var
	 */
	protected $_type;


	/**
	 * @var Collection
	 */
	protected $_fields;


	/**
	 * Decay constructor.
	 * @param string $type
	 */
	public function __construct(string $type){
		$this->setType($type);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $name
	 * @param $arguments
	 * @return PredicateSetInterface
	 */
	public function __call($name, $arguments) : PredicateSetInterface{
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
	 * @return \ElasticSearchPredicate\Predicate\PredicateSetInterface
	 */
	public function __get($name) : PredicateSetInterface{
		$_name = strtolower($name);
		if($_name === 'predicate'){
			return $this->getPredicate();
		}

		return $this->getPredicate()->{$name};
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param \ElasticSearchPredicate\Predicate\ScoreFunction\Field\FieldInterface $field
	 * @return \ElasticSearchPredicate\Predicate\ScoreFunction\Decay
	 */
	public function addField(FieldInterface $field) : Decay{
		$this->_fields = $this->getFields()->append($field);

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return string
	 */
	public function getType() : string{
		return $this->_type;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $type
	 * @return \ElasticSearchPredicate\Predicate\ScoreFunction\Decay
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setType(string $type) : Decay{
		if(!in_array($type, [
			'linear',
			'exp',
			'gauss',
		])
		){
			throw new PredicateException('Invalid decay function');
		}
		$this->_type = $type;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function toArray() : array{
		$_fields = $this->getFields();
		if($_fields->isEmpty()){
			throw new PredicateException('Decay should contain at least one field');
		}
		$_type = $this->_type;

		$_ret = [
			$_type => $this->getFields()->map(function(FieldInterface $item){
				return $item->toArray();
			})->flatten(1)->toArray(),
		];

		if(!empty($_query = $this->getQuery())){
			$_ret['filter'] = $_query;
		}

		if(!empty($this->_weight)){
			$_ret['weight'] = $this->_weight;
		}

		return $_ret;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \DusanKasan\Knapsack\Collection
	 */
	public function getFields() : Collection{
		if(!isset($this->_fields)){
			return $this->_fields = new Collection([]);
		}

		return $this->_fields;
	}


}