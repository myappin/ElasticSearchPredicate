<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 17. 6. 2016
 * Time: 10:52
 */

namespace ElasticSearchPredicate\Predicate\FunctionScore;

use ElasticSearchPredicate\Endpoint\Query\QueryTrait;
use ElasticSearchPredicate\Predicate\FunctionScore\Weight\WeightTrait;
use ElasticSearchPredicate\Predicate\PredicateSet;

/**
 * Class AbstractFunction
 * @package   ElasticSearchPredicate\Predicate\FunctionScore
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @property PredicateSet predicate
 * @property PredicateSet AND
 * @property PredicateSet and
 * @property PredicateSet OR
 * @property PredicateSet or
 */
abstract class AbstractFunction implements FunctionInterface {
    
    
    use QueryTrait, WeightTrait;
    
    /**
     * @param $name
     * @param $arguments
     * @return PredicateSet
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function __call($name, $arguments): PredicateSet {
        if (empty($arguments)) {
            return $this->getPredicate()->$name();
        }
        
        return $this->getPredicate()->$name(...$arguments);
    }
    
    
    /**
     * @param $name
     * @return PredicateSet
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function __get($name): PredicateSet {
        $_name = strtolower($name);
        if ($_name === 'predicate' || $_name === 'predicates') {
            return $this->getPredicate();
        }
        
        return $this->getPredicate()->{$name};
    }
    
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    abstract public function toArray(): array;
    
    
}
