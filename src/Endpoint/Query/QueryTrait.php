<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 13:19
 */

namespace ElasticSearchPredicate\Endpoint\Query;

use ElasticSearchPredicate\Predicate\PredicateSet;

/**
 * Class QueryTrait
 * @package   ElasticSearchPredicate\Endpoint\Query
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait QueryTrait {
    
    
    /**
     * @var PredicateSet
     */
    protected PredicateSet $_predicates;
    
    
    /**
     * @return PredicateSet
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getPredicate(): PredicateSet {
        return $this->_predicates ?? ($this->_predicates = new PredicateSet());
    }
    
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getQuery(): array {
        return $this->getPredicate()->toArray();
    }
    
    
}
