<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 10:28
 */

namespace ElasticSearchPredicate\Predicate\ScoreFunction\Weight;


/**
 * Interface WeightInterface
 * @package ElasticSearchPredicate\Predicate\ScoreFunction\Weigh
 */
interface WeightInterface {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param $weight
	 * @return $this
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 */
	public function setWeight($weight);


}