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

namespace ElasticSearchPredicate\Endpoint\ScoreFunction\SFunction;


use DusanKasan\Knapsack\Collection;
use ElasticSearchPredicate\Endpoint\ScoreFunction\SFunction\Field\FieldInterface;
use ElasticSearchPredicate\Predicate\PredicateException;


/**
 * Class Decay
 * @package   ElasticSearchPredicate\Endpoint\ScoreFunction\SFunction
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Decay extends AbstractFunction {


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
	 * @param \ElasticSearchPredicate\Endpoint\ScoreFunction\SFunction\Field\FieldInterface $field
	 * @return \ElasticSearchPredicate\Endpoint\ScoreFunction\SFunction\Decay
	 */
	public function addField(FieldInterface $field) : Decay{
		$this->getFields()->append($field);

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
	 * @return \ElasticSearchPredicate\Endpoint\ScoreFunction\SFunction\Decay
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
	 */
	public function toArray() : array{
		$_type = $this->_type;

		return [
			$_type => $this->getFields()->map(function(FieldInterface $item){
				return $item->toArray();
			})->toArray(),
		];
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