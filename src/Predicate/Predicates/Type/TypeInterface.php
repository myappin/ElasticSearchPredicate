<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 25. 5. 2016
 * Time: 22:47
 */

namespace ElasticSearchPredicate\Predicate\Predicates\Type;

use ElasticSearchPredicate\Predicate\Predicates\PredicateInterface;

/**
 * Interface TypeInterface
 * @package ElasticSearchPredicate\Predicate\Predicates\Type
 */
interface TypeInterface {


    /**
     * @param string $type
     * @return mixed
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function type(string $type): PredicateInterface;


}
