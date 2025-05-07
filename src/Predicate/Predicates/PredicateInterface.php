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

namespace ElasticSearchPredicate\Predicate\Predicates;

/**
 * Interface PredicateInterface
 * @package ElasticSearchPredicate\Predicate\Predicates
 */
interface PredicateInterface {


    /**
     * @param string $combiner
     * @return \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function setCombiner(string $combiner): PredicateInterface;


    /**
     * @return string
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getCombiner(): string;


    /**
     * @param string $path
     * @return \ElasticSearchPredicate\Predicate\Predicates\PredicateInterface
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function pathFix(string $path): PredicateInterface;


    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function toArray(): array;


}
