<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 10:00
 */

namespace ElasticSearchPredicate\Predicate\Predicates;


use ElasticSearchPredicate\Predicate\PredicateException;
use ElasticSearchPredicate\Predicate\Predicates\Type\TypeInterface;


/**
 * Class MultiMatch
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class MultiMatch extends AbstractPredicate implements TypeInterface {


	/**
	 * @var string
	 */
	protected $_fields;


	/**
	 * @var bool|float|int|string
	 */
	protected $_query;


	/**
	 * @var string
	 */
	protected $_type;


	/**
	 * MultiMatch constructor.
	 * @param            $query
	 * @param array      $fields
	 * @param array      $options
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function __construct($query, array $fields, array $options = []){
		if(!is_scalar($query)){
			throw new PredicateException('MultiMatch value must be scalar');
		}

		if(!empty($fields)){
			if(is_array($fields)){
				foreach($fields as $field){
					if(!is_scalar($field)){
						throw new PredicateException('Filed must be scalar');
					}
				}
				$this->_fields = $fields;
			}
			elseif(is_string($fields)){
				$this->_fields = [$fields];
			}
			else{
				throw new PredicateException('Unexpected field type');
			}
		}
		else{
			throw new PredicateException('Fields can not be empty set');
		}

		$this->_query = $query;

		$this->configure($options);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $type
	 * @return \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function type(string $type) : PredicateInterface{
		if(!in_array($type, [
			'phrase',
			'phrase_prefix',
		])
		){
			throw new PredicateException('Type is not valid');
		}

		$this->_type   = $type;
		$this->_simple = false;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function toArray() : array{
		$_ret = [
			'multi_match' => [
				'query' => $this->_query,
			],
		];

		if(!empty($this->_fields)){
			$_ret['multi_match']['fields'] = $this->_fields;
		}

		if(!empty($this->_type)){
			$_ret['multi_match']['type'] = $this->_type;
		}

		return $_ret;
	}
}