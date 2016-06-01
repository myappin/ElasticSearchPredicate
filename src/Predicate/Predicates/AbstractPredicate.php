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

namespace ElasticSearchPredicate\Predicate\Predicates;


use ElasticSearchPredicate\Predicate\PredicateException;
use ElasticSearchPredicate\Predicate\PredicateSet;


/**
 * Class AbstractPredicate
 * @package   ElasticSearchPredicate\Predicate\Predicates
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
	protected $_other_options = [];


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return string
	 */
	public function getCombiner() : string{
		return $this->_combiner;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $combiner
	 * @return \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface
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
		$_implements = class_implements($this);
		if(!empty($options)){
			foreach($options as $key => $opt){
				if(in_array($key, $this->_other_options)){
					if(empty($opt)){
						call_user_func([
										   $this,
										   $key,
									   ]);
					}
					else{
						if(is_scalar($opt)){
							$opt = [$opt];
						}
						call_user_func_array([
												 $this,
												 $key,
											 ], $opt);
					}
					continue;
				}
				$_method = strtolower($key);
				$_key    = ucfirst($_method);
				if(!in_array('ElasticSearchPredicate\Predicate\\Predicates\\' . $_key . '\\' . $_key . 'Interface', $_implements)){
					continue;
				}
				if(empty($opt)){
					call_user_func([
									   $this,
									   $_method,
								   ]);
				}
				else{
					if(is_scalar($opt)){
						$opt = [$opt];
					}
					call_user_func_array([
											 $this,
											 $_method,
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