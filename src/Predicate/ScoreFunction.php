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
		$this->getFunctions()->append($function);

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
			'query' => [
				'score_function' => [
					'query' => parent::toArray(),
				],
			],
		];

		if($_functions->sizeIs(1)){
			$_ret['query']['score_function']['FUNCTION'] = $_functions->current()->toArray();
		}
		else{
			$_ret['query']['score_function']['functions'] = $_functions->toArray();
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