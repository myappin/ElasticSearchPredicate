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


use ElasticSearchPredicate\Predicate\Filtered;
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
    public function getFilteredPredicate() : Filtered {
        if (!$this->_predicate instanceof Filtered) {
            if ($this->_predicate instanceof PredicateSet) {
                $_filtered = new Filtered();
                $_filtered->setCombiner($this->_predicate->getCombiner());
                $_filtered->setPredicates($this->_predicate->getPredicates());

                return $this->_predicate = $_filtered;
            }
            else {
                return $this->_predicate = new Filtered();
            }
        }

        return $this->_predicate;
    }


}