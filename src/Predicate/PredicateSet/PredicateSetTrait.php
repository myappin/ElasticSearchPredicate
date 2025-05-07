<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 30. 5. 2016
 * Time: 15:08
 */

namespace ElasticSearchPredicate\Predicate\PredicateSet;

/**
 * Class PredicateSetTrait
 * @package   ElasticSearchPredicate\Predicate\PredicateSet
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait PredicateSetTrait {


    /**
     * @param string           $term
     * @param int|float|string $from
     * @param int|float|string $to
     * @param array            $options
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function between(string $term, int|float|string $from, int|float|string $to, array $options = []): self {
        return $this->Range($term, $from, $to, $options);
    }


    /**
     * @param string           $term
     * @param int|float|string $value
     * @param array            $options
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function equalTo(string $term, int|float|string $value, array $options = []): self {
        return $this->Term($term, $value, $options);
    }


    /**
     * @param string           $term
     * @param int|float|string $from
     * @param array            $options
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function greaterThan(string $term, int|float|string $from, array $options = []): self {
        return $this->Range($term, $from, null, array_merge($options, [
            'types' => [
                'gt',
                'lt',
            ],
        ]));
    }


    /**
     * @param string           $term
     * @param int|float|string $from
     * @param array            $options
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function greaterThanOrEqualTo(string $term, int|float|string $from, array $options = []): self {
        return $this->Range($term, $from, null, array_merge($options, [
            'types' => [
                'gte',
                'lte',
            ],
        ]));
    }


    /**
     * @param string           $term
     * @param int|float|string $to
     * @param array            $options
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function lessThan(string $term, int|float|string $to, array $options = []): self {
        return $this->Range($term, null, $to, array_merge($options, [
            'types' => [
                'gt',
                'lt',
            ],
        ]));
    }


    /**
     * @param string           $term
     * @param int|float|string $to
     * @param array            $options
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function lessThanOrEqualTo(string $term, int|float|string $to, array $options = []): self {
        return $this->Range($term, null, $to, array_merge($options, [
            'types' => [
                'gte',
                'lte',
            ],
        ]));
    }


}
