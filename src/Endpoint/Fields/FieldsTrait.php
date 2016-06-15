<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 13:19
 */

namespace ElasticSearchPredicate\Endpoint\Fields;


use ElasticSearchPredicate\Endpoint\EndpointException;

/**
 * Class FieldsTrait
 * @package   ElasticSearchPredicate\Endpoint\Fields
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait FieldsTrait {


	/**
	 * @var array
	 */
	protected $_fields = [];


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param array $fields
	 * @return \ElasticSearchPredicate\Endpoint\Fields\FieldsInterface
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 */
	public function fields(array $fields) : FieldsInterface{
		foreach($fields as $field){
			if(!is_string($field)){
				throw new EndpointException('Fields should by array of string');
			}
		}
		$this->_fields = $fields;

		return $this;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 */
	public function getFields() : array{
		return $this->_fields;
	}


}