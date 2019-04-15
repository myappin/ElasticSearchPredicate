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

namespace ElasticSearchPredicate\Endpoint\Filtered;


use ElasticSearchPredicate\Predicate\FilterPredicateSet;
use ElasticSearchPredicate\Predicate\PredicateSet;


/**
 * Class FilteredTrait
 * @package   ElasticSearchPredicate\Endpoint\Filtered
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait FilteredTrait {


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @return \ElasticSearchPredicate\Predicate\FunctionScore
     */
    public function getFilteredPredicate() : FilterPredicateSet {
        if (!$this->_predicates instanceof FilterPredicateSet) {
            if ($this->_predicates instanceof PredicateSet) {
                $_filtered = new FilterPredicateSet();
                $_filtered->setCombiner($this->_predicates->getCombiner());
                $_filtered->setPredicates($this->v->getPredicates());

                return $this->_predicates = $_filtered;
            }
            else {
                return $this->_predicates = new FilterPredicateSet();
            }
        }

        return $this->_predicates;
    }


}
