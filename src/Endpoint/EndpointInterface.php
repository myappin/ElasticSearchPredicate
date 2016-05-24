<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 9:37
 */

namespace ElasticSearchPredicate\Endpoint;


/**
 * Interface EndpointInterface
 * @package ElasticSearchPredicate\Endpoint
 */
interface EndpointInterface {


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function execute() : array;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function getPreparedParams() : array;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return EndpointInterface
	 */
	public function clearParams() : EndpointInterface;


}