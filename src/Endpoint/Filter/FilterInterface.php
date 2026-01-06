<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 10:28
 */

namespace ElasticSearchPredicate\Endpoint\Filter;

use ElasticSearchPredicate\Predicate\PredicateSet;

/**
 * Interface FilterInterface
 * @package ElasticSearchPredicate\Endpoint\Filter
 */
interface FilterInterface {
    
    
    /**
     * @return PredicateSet
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getFilterPredicate(): PredicateSet;
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getFilter(): array;
    
    
}
