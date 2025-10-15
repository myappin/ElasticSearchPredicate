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
 * Time: 22:47
 */

namespace ElasticSearchPredicate\Predicate\Predicates\Operator;

use ElasticSearchPredicate\Predicate\Predicates\PredicateInterface;

/**
 * Interface OperatorInterface
 * @package ElasticSearchPredicate\Predicate\Predicates\Operator
 */
interface OperatorInterface {
    
    
    /**
     * @param string $operator
     * @return mixed
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function operator(string $operator): PredicateInterface;
    
    
}
