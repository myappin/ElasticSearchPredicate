<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 27. 5. 2016
 * Time: 16:51
 */

namespace ElasticSearchPredicate\Predicate;

use JetBrains\PhpStorm\ArrayShape;

/**
 * Class FilterPredicateSet
 * nested mappings
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class FilterPredicateSet extends PredicateSet {
    
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    #[ArrayShape(['bool' => "array"])]
    public function toArray(): array {
        return [
            'bool' => [
                'filter' => parent::toArray(),
            ],
        ];
    }
    
    
}
