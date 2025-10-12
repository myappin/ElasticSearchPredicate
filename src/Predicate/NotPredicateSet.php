<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 9:55
 */

namespace ElasticSearchPredicate\Predicate;

use ElasticSearchPredicate\Predicate\Predicates\PredicateInterface;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class NotPredicateSet
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class NotPredicateSet extends PredicateSet {
    
    
    /**
     * @param string $combiner
     * @return $this
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setCombiner(string $combiner): self {
        if (strtoupper($combiner) === 'or') {
            throw new PredicateException('Not allowed combiner inside not predicate');
        }
        
        return parent::setCombiner($combiner);
    }
    
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    #[ArrayShape(['bool' => "array"])]
    public function toArray(): array {
        return [
            'bool' => [
                'must_not' => $this->_predicates->map(function (PredicateInterface $predicate) {
                    return $predicate->toArray();
                })->values()->toArray(),
            ],
        ];
    }
    
    
}
