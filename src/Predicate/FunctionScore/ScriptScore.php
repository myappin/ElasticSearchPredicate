<?php
declare(strict_types=1);
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
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class ScriptScore
 * @package   ElasticSearchPredicate\Predicate\FunctionScore
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class ScriptScore extends AbstractFunction {
    
    
    /**
     * @var array
     */
    protected array $_script;
    
    
    /**
     * @var array
     */
    protected array $_params = [];
    
    
    /**
     * ScriptScore constructor.
     * @param array $script
     * @param array $params
     * @throws PredicateException
     */
    public function __construct(array $script, array $params = []) {
        $this->setScript($script);
        $this->setParams($params);
    }
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getScript(): array {
        return $this->_script;
    }
    
    /**
     * @param array $script
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setScript(array $script): self {
        if (isset($script['inline'])) {
            $script['inline'] = trim($script['inline']);
        }
        
        $this->_script = $script;
        
        return $this;
    }
    
    /**
     * @param array $params
     * @return $this
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setParams(array $params): self {
        foreach ($params as $key => $item) {
            if (!is_string($key)) {
                throw new PredicateException('Wrong parameter key type');
            }
            if (!is_scalar($item)) {
                throw new PredicateException('Wrong parameter value type');
            }
        }
        
        $this->_params = $params;
        
        return $this;
    }
    
    /**
     * @return array
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    #[ArrayShape([
        'script_score' => "array",
        'weight'       => "int|float",
        'filter'       => "array",
    ])]
    public function toArray(): array {
        $_ret = [
            'script_score' => [
                'script' => $this->_script,
            ],
        ];
        
        if (!empty($_params = $this->_params)) {
            $_ret['script_score']['script']['params'] = $_params;
        }
        
        if (!empty($_query = $this->getQuery())) {
            $_ret['filter'] = $_query;
        }
        
        if (!empty($this->_weight)) {
            $_ret['weight'] = $this->_weight;
        }
        
        return $_ret;
    }
    
    
}
