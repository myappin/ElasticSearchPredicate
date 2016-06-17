<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 17. 6. 2016
 * Time: 11:05
 */

namespace ElasticSearchPredicate\Predicate\ScoreFunction\Field;
use ElasticSearchPredicate\Predicate\PredicateException;


/**
 * Class Field
 * @package   ElasticSearchPredicate\Predicate\ScoreFunction\Field
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Field implements FieldInterface {


	/**
	 * @var string
	 */
	protected $_name;


	/**
	 * @var int|float|string
	 */
	protected $_origin;


	/**
	 * @var int|float|string
	 */
	protected $_scale;


	/**
	 * @var int
	 */
	protected $_offset;


	/**
	 * @var int|float
	 */
	protected $_decay;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $name
	 * @param        $origin
	 * @param        $scale
	 * @param null   $offset
	 * @param null   $decay
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function __construct(string $name, $origin, $scale, $offset = null, $decay = null){
		$this->setName($name);
		$this->setOrigin($origin);
		$this->setScale($scale);
		if($offset !== null){
			$this->setOffset($offset);
		}
		if($decay !== null){
			$this->setDecay($decay);
		}
	}


	/**
	 * @return string
	 */
	public function getName(){
		return $this->_name;
	}


	/**
	 * @param string $name
	 * @return Field
	 */
	public function setName(string $name){
		$this->_name = $name;

		return $this;
	}


	/**
	 * @return float|int|string
	 */
	public function getOrigin(){
		return $this->_origin;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $origin
	 * @return \ElasticSearchPredicate\Predicate\ScoreFunction\Field\Field
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setOrigin($origin) : Field{
		if(!is_scalar($origin)){
			throw new PredicateException('Origin should be scalar');
		}
		$this->_origin = $origin;

		return $this;
	}


	/**
	 * @return float|int|string
	 */
	public function getScale(){
		return $this->_scale;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $scale
	 * @return \ElasticSearchPredicate\Predicate\ScoreFunction\Field\Field
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setScale($scale) : Field{
		if(!is_scalar($scale)){
			throw new PredicateException('Scale should be scalar');
		}
		$this->_scale = $scale;

		return $this;
	}


	/**
	 * @return int
	 */
	public function getOffset(){
		return $this->_offset;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $offset
	 * @return \ElasticSearchPredicate\Predicate\ScoreFunction\Field\Field
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setOffset($offset) : Field{
		if(!is_int($offset)){
			throw new PredicateException('Offset should be integer');
		}
		$this->_offset = $offset;

		return $this;
	}


	/**
	 * @return float|int
	 */
	public function getDecay(){
		return $this->_decay;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $decay
	 * @return \ElasticSearchPredicate\Predicate\ScoreFunction\Field\Field
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setDecay($decay) : Field{
		if(!is_int($decay) && !is_float($decay)){
			throw new PredicateException('Decay should be numeric');
		}
		$this->_decay = $decay;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		$_name = $this->_name;
		$_ret  = [
			$_name => [
				'origin' => $this->_origin,
				'scale'  => $this->_scale,
			],
		];

		if(isset($this->_offset)){
			$_ret[$_name]['offset'] = $this->_offset;
		}
		if(isset($this->_decay)){
			$_ret[$_name]['decay'] = $this->_decay;
		}

		return $_ret;
	}


}