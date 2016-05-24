<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 9:36
 */

namespace ElasticSearchPredicate\Predicate;


/**
 * Class AbstractPredicate
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
abstract class AbstractPredicate implements PredicateInterface {


	/**
	 * @var string
	 */
	protected $_combiner = PredicateSet::C_AND;


	/**
	 * @var array
	 */
	protected $_allowed_options = [];


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $combiner
	 * @return \ElasticSearchPredicate\Predicate\PredicateInterface
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setCombiner(string $combiner) : PredicateInterface{
		if($combiner !== PredicateSet::C_AND && $combiner !== PredicateSet::C_OR){
			throw new PredicateException('Unsupported combiner');
		}
		$this->_combiner = $combiner;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param array $options
	 */
	public function configure(array $options){
		if(!empty($options)){
			foreach($options as $key => $opt){
				$key = strtolower($key);
				if(!in_array($key, $this->_allowed_options)){
					continue;
				}
				if(empty($opt)){
					call_user_func([
									   $this,
									   $key,
								   ]);
				}
				else{
					call_user_func_array([
											 $this,
											 $key,
										 ], $opt);
				}
			}
		}
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	abstract public function toArray() : array;


}