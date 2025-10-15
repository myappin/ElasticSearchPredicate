<?php
/*
 * *
 *  * MyAppIn (http://www.myappin.com)
 *  * @author    Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
 *  * @link      http://www.myappin.com
 *  * @copyright Copyright (c) 2025 MyAppIn s.r.o. (http://www.myappin.com)
 *
 */

declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 25. 5. 2016
 * Time: 22:42
 */

namespace ElasticSearchPredicate\Predicate\Predicates\Boost;

use ElasticSearchPredicate\Predicate\Predicates\PredicateInterface;

/**
 * Interface BoostInterface
 * @package ElasticSearchPredicate\Predicate\Predicates\Boost
 */
interface BoostInterface {
    
    
    /**
     * @param int|float $boost
     * @return PredicateInterface
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function boost(int|float $boost): PredicateInterface;
    
    
}
