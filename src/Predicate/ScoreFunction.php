<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 27. 5. 2016
 * Time: 16:51
 */

namespace ElasticSearchPredicate\Predicate;


use DusanKasan\Knapsack\Collection;
use ElasticSearchPredicate\Endpoint\ScoreFunction\SFunction\FunctionInterface;


/**
 * Class ScoreFunction
 * nested mappings
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class ScoreFunction extends PredicateSet {


	/**
	 * @var float|int
	 */
	protected $_max_boost = null;


	/**
	 * @var string
	 */
	protected $_score_mode = null;


	/**
	 * @var string
	 */
	protected $_boost_mode = null;


	/**
	 * @var float|int
	 */
	protected $_min_score = null;


	/**
	 * @var Collection
	 */
	protected $_functions;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param \ElasticSearchPredicate\Endpoint\ScoreFunction\SFunction\FunctionInterface $function
	 * @return \ElasticSearchPredicate\Predicate\ScoreFunction
	 */
	public function addFunction(FunctionInterface $function) : ScoreFunction{
		$this->_functions = $this->getFunctions()->append($function);

		return $this;
	}


	/**
	 * @return float|int
	 */
	public function getMaxBoost(){
		return $this->_max_boost;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $max_boost
	 * @return \ElasticSearchPredicate\Predicate\ScoreFunction
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setMaxBoost($max_boost) : ScoreFunction{
		if(is_float($max_boost) && is_int($max_boost)){
			throw new PredicateException('Max boost should be int or float');
		}
		$this->_max_boost = $max_boost;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getBoostMode(){
		return $this->_boost_mode;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $boost_mode
	 * @return \ElasticSearchPredicate\Predicate\ScoreFunction
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setBoostMode($boost_mode) : ScoreFunction{
		if(!in_array($boost_mode, [
			'multiply',
			'replace',
			'sum',
			'avg',
			'max',
			'min',
		])
		){
			throw new PredicateException('Invalid boost type');
		}
		$this->_boost_mode = $boost_mode;

		return $this;
	}


	/**
	 * @return float|int
	 */
	public function getMinScore(){
		return $this->_min_score;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $min_score
	 * @return \ElasticSearchPredicate\Predicate\ScoreFunction
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setMinScore($min_score) : ScoreFunction{
		if(is_float($min_score) && is_int($min_score)){
			throw new PredicateException('Min score should be int or float');
		}
		$this->_min_score = $min_score;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getScoreMode(){
		return $this->_score_mode;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $score_mode
	 * @return \ElasticSearchPredicate\Predicate\ScoreFunction
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setScoreMode($score_mode) : ScoreFunction{
		if(!in_array($score_mode, [
			'multiply',
			'sum',
			'avg',
			'first',
			'max',
			'min',
		])
		){
			throw new PredicateException('Invalid score mode');
		}
		$this->_score_mode = $score_mode;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function toArray() : array{
		$_functions = $this->getFunctions();
		if($_functions->isEmpty()){
			throw new PredicateException('FunctionScore should contain at least one function');
		}

		$_ret = [
			'function_score' => [
			],
		];

		if(!empty($_query = parent::toArray())){
			$_ret['function_score']['query'] = $_query;
		}

		if(isset($this->_boost_mode)){
			$_ret['function_score']['boost_mode'] = $this->_boost_mode;
		}
		if(isset($this->_max_boost)){
			$_ret['function_score']['max_boost'] = $this->_max_boost;
		}
		if(isset($this->_score_mode)){
			$_ret['function_score']['score_mode'] = $this->_score_mode;
		}
		if(isset($this->_min_score)){
			$_ret['function_score']['min_score'] = $this->_min_score;
		}

		if($_functions->sizeIs(1)){
			$_ret['function_score'] = array_merge($_ret['function_score'], $_functions->first()->toArray());
		}
		else{
			$_ret['function_score']['functions'] = $_functions->map(function(FunctionInterface $item){
				return $item->toArray();
			})->toArray();
		}

		return $_ret;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \DusanKasan\Knapsack\Collection
	 */
	public function getFunctions() : Collection{
		if(!isset($this->_functions)){
			return $this->_functions = new Collection([]);
		}

		return $this->_functions;
	}


}