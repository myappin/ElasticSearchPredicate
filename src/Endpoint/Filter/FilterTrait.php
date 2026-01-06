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

namespace ElasticSearchPredicate\Endpoint\Filter;

use ElasticSearchPredicate\Predicate\PredicateSet;

/**
 * Class FilterTrait
 * @package   ElasticSearchPredicate\Endpoint\Filter
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait FilterTrait {
    
    
    /**
     * @var PredicateSet
     */
    protected PredicateSet $_filter_predicates;
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getFilter(): array {
        return $this->getFilterPredicate()->toArray();
    }
    
    /**
     * @return PredicateSet
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getFilterPredicate(): PredicateSet {
        return $this->_filter_predicates ?? ($this->_filter_predicates = new PredicateSet());
    }
    
    
}
