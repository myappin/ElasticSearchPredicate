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
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		return [
			'query' => [
				'score_function' => [
					'query' => parent::toArray(),
				],
			],
		];
	}


}