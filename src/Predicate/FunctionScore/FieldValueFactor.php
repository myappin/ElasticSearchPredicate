<?php
declare(strict_fields = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 17. 6. 2016
 * Time: 10:51
 */

namespace ElasticSearchPredicate\Predicate\FunctionScore;


use ElasticSearchPredicate\Predicate\PredicateException;


/**
 * Class FieldValueFactor
 * @package   ElasticSearchPredicate\Predicate\FunctionScore
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class FieldValueFactor extends AbstractFunction {


	/**
	 * @var
	 */
	protected $_field;


	/**
	 * @var int|float
	 */
	protected $_factor;


	/**
	 * @var string
	 */
	protected $_modifier = '';


	/**
	 * @var int
	 */
	protected $_missing = 1;


	/**
	 * FieldValueFactor constructor.
	 * @param string      $field
	 * @param null        $factor
	 * @param string|null $modifier
	 */
	public function __construct(string $field, $factor = null, string $modifier = null){
		$this->setField($field);
		$this->setFactor($factor);
		$this->setModifier($modifier);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return string
	 */
	public function getField() : string{
		return $this->_field;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $field
	 * @return \ElasticSearchPredicate\Predicate\FunctionScore\FieldValueFactor
	 */
	public function setField(string $field) : FieldValueFactor{
		$this->_field = $field;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return int
	 */
	public function getMissing() : int{
		return $this->_missing;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param int $missing
	 * @return \ElasticSearchPredicate\Predicate\FunctionScore\FieldValueFactor
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setMissing(int $missing) : FieldValueFactor{
		if(!in_array($missing, [
			1,
			0,
		])
		){
			throw new PredicateException('Missing should be int between 0 and 1');
		}
		$this->_missing = $missing;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return float|int
	 */
	public function getFactor(){
		return $this->_factor;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $factor
	 * @return \ElasticSearchPredicate\Predicate\FunctionScore\FieldValueFactor
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setFactor($factor) : FieldValueFactor{
		if(!is_int($factor) && !is_float($factor)){
			throw new PredicateException('Factor should be int of float');
		}
		$this->_factor = $factor;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return string
	 */
	public function getModifier() : string{
		return $this->_modifier;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $modifier
	 * @return \ElasticSearchPredicate\Predicate\FunctionScore\FieldValueFactor
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setModifier(string $modifier) : FieldValueFactor{
		if(!in_array($modifier, [
			'none',
			'log',
			'log1p',
			'log2p',
			'ln',
			'ln1p',
			'ln2p',
			'square',
			'sqrt',
			'reciprocal',
		])
		){
			throw new PredicateException('Modifier is not supported');
		}
		$this->_modifier = $modifier;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function toArray() : array{
		$_ret = [
			'field_value_factor' => [
				'field' => $this->_field,
			],
		];

		if(isset($this->_factor)){
			$_ret['field_value_factor']['factor'] = $this->_factor;
		}
		if(!empty($this->_modifier)){
			$_ret['field_value_factor']['modifier'] = $this->_modifier;
		}

		$_ret['field_value_factor']['missing'] = $this->_missing;

		if(!empty($_query = $this->getQuery())){
			$_ret['filter'] = $_query;
		}

		if(!empty($this->_weight)){
			$_ret['weight'] = $this->_weight;
		}

		return $_ret;
	}


}