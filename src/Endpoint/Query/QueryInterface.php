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

namespace ElasticSearchPredicate\Endpoint\Query;

use ElasticSearchPredicate\Predicate\PredicateSet;

/**
 * Interface QueryInterface
 * @package ElasticSearchPredicate\Endpoint\Query
 */
interface QueryInterface {


    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getQuery(): array;


    /**
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getPredicate(): PredicateSet;


}
