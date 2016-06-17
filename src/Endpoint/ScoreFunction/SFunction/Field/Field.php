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

namespace ElasticSearchPredicate\Endpoint\ScoreFunction\SFunction\Field;


/**
 * Class Field
 * @package   ElasticSearchPredicate\Endpoint\ScoreFunction\SFunction\Field
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Field {


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
	 * @return string
	 */
	public function getName(){
		return $this->_name;
	}


	/**
	 * @param string $name
	 * @return Field
	 */
	public function setName($name){
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
	 * @param float|int|string $origin
	 * @return Field
	 */
	public function setOrigin($origin){
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
	 * @param float|int|string $scale
	 * @return Field
	 */
	public function setScale($scale){
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
	 * @param int $offset
	 * @return Field
	 */
	public function setOffset($offset){
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
	 * @param float|int $decay
	 * @return Field
	 */
	public function setDecay($decay){
		$this->_decay = $decay;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		return [
		];
	}


}