<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 9:41
 */

namespace ElasticSearchPredicate\Predicate;

use DusanKasan\Knapsack\Collection;
use ElasticSearchPredicate\Predicate\Predicates\PredicateInterface;

/**
 * Interface PredicateSetInterface
 * @package ElasticSearchPredicate\Predicate
 */
interface PredicateSetInterface extends PredicateInterface {


    /**
     * @return Collection
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getPredicates(): Collection;


    /**
     * @param \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface $predicate
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function andPredicate(PredicateInterface $predicate): PredicateSet;


    /**
     * @param \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface $predicate
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function orPredicate(PredicateInterface $predicate): PredicateSet;


    /**
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function nest(): PredicateSet;


    /**
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function unnest(): PredicateSet;


}
