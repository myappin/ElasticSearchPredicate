<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 17. 6. 2016
 * Time: 10:51
 */

namespace ElasticSearchPredicate\Predicate\FunctionScore;


use ElasticSearchPredicate\Predicate\PredicateException;


/**
 * Class ScriptScore
 * @package   ElasticSearchPredicate\Predicate\FunctionScore
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class ScriptScore extends AbstractFunction {


	/**
	 * @var
	 */
	protected $_script;


	/**
	 * @var array
	 */
	protected $_params = [];


	/**
	 * ScriptScore constructor.
	 * @param string $script
	 * @param array  $params
	 */
	public function __construct(string $script, array $params = []){
		$this->setScript($script);
		$this->setParams($params);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param array $params
	 * @return \ElasticSearchPredicate\Predicate\FunctionScore\ScriptScore
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function setParams(array $params) : ScriptScore{
		foreach($params as $key => $item){
			if(!is_string($key)){
				throw new PredicateException('Wrong parameter key type');
			}
			if(!is_scalar($item)){
				throw new PredicateException('Wrong parameter value type');
			}
		}
		$this->_params = $params;

		return $this;
	}


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @return string
     */
    public function getScript() : string {
        return $this->_script;
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @param string $script
     * @return \ElasticSearchPredicate\Predicate\FunctionScore\ScriptScore
     */
    public function setScript(string $script) : ScriptScore {
        $this->_script = $script;

        return $this;
    }


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return array
	 * @throws \ElasticSearchPredicate\Predicate\PredicateException
	 */
	public function toArray() : array{
		$_ret = [
			'script_score' => [
                'script' => [
                    'lang'   => 'groovy',
                    'inline' => $this->_script,
                ],
			],
		];

		if(!empty($_params = $this->_params)){
            $_ret['script_score']['script']['params'] = $_params;
		}

		if(!empty($_query = $this->getQuery())){
			$_ret['filter'] = $_query;
		}

		if(!empty($this->_weight)){
			$_ret['weight'] = $this->_weight;
		}

		return $_ret;
	}


}