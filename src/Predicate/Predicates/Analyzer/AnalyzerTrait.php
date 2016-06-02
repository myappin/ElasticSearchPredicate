<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 25. 5. 2016
 * Time: 22:36
 */

namespace ElasticSearchPredicate\Predicate\Predicates\Analyzer;


use ElasticSearchPredicate\Predicate\Predicates\PredicateInterface;


/**
 * Class AnalyzerTrait
 * @package   ElasticSearchPredicate\Predicate\Predicates\Analyzer
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait AnalyzerTrait {


	/**
	 * @var string
	 */
	protected $_analyzer;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $analyzer
	 * @return \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function analyzer(string $analyzer) : PredicateInterface{
		$this->_analyzer = $analyzer;
		$this->_simple   = false;

		return $this;
	}


}