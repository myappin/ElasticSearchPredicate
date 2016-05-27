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
 * Class Nested
 * nested mappings
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Nested extends PredicateSet {


	/**
	 * @var
	 */
	protected $_path;


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $path
	 * @return \ElasticSearchPredicate\Predicate\Nested
	 */
	public function setPath(string $path) : Nested{
		$this->_path = $path;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		return [
			'nested' => [
				'path'  => $this->_path,
				'query' => parent::toArray(),
			],
		];
	}


}