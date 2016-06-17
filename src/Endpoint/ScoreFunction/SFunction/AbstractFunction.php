<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 17. 6. 2016
 * Time: 10:52
 */

namespace ElasticSearchPredicate\Endpoint\ScoreFunction\SFunction;


/**
 * Class AbstractFunction
 * @package   ElasticSearchPredicate\Endpoint\ScoreFunction\SFunction
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
abstract class AbstractFunction implements FunctionInterface {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	abstract public function toArray() : array;


}