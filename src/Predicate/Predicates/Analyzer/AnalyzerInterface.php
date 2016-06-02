<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 25. 5. 2016
 * Time: 22:47
 */

namespace ElasticSearchPredicate\Predicate\Predicates\Analyzer;


use ElasticSearchPredicate\Predicate\Predicates\PredicateInterface;


/**
 * Interface AnalyzerInterface
 * @package ElasticSearchPredicate\Predicate\Predicates\Analyzer
 */
interface AnalyzerInterface {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $analyzer
	 * @return mixed
	 */
	public function analyzer(string $analyzer) : PredicateInterface;


}