<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.com)
 * @author    Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
 * @link      http://www.myappin.com
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.com)
 */

namespace ElasticSearchPredicate\Predicate\PredicateSet;


/**
 * Class InnerHitsInterface
 * @package   ElasticSearchPredicate\Predicate\PredicateSet
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
interface InnerHitsInterface {


    /**
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     * @return bool
     */
    public function hasInnerHits() : bool;


}