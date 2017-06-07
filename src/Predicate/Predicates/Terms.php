<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 10:00
 */

namespace ElasticSearchPredicate\Predicate\Predicates;


use ElasticSearchPredicate\Predicate\PredicateException;
use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostInterface;
use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostTrait;
use ElasticSearchPredicate\Predicate\Predicates\Simple\SimpleInterface;
use ElasticSearchPredicate\Predicate\Predicates\Simple\SimpleTrait;


/**
 * Class Terms
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Terms extends AbstractPredicate implements BoostInterface, SimpleInterface {


    use BoostTrait, SimpleTrait;


    /**
     * @var string
     */
    protected $_term;


    /**
     * @var array
     */
    protected $_values;


    /**
     * Terms constructor.
     * @param string $term
     * @param array  $values
     * @param array  $options
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     */
    public function __construct(string $term, array $values, array $options = []) {
        $this->_term = $term;

        foreach ($values as $val) {
            if (!is_scalar($val) && $val !== null) {
                throw new PredicateException('Term values must be scalar');
            }
        }

        $this->_values = $values;

        $this->configure($options);
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @return array
     */
    public function toArray() : array {
        $_term = $this->_term;
        if ($this->_simple) {
            return [
                'terms' => [
                    $_term => $this->_values,
                ],
            ];
        }

        $_ret = [
            'terms' => [
                $_term => [
                    'value' => $this->_values,
                ],
            ],
        ];

        if (!empty($this->_boost)) {
            $_ret['terms'][$_term]['boost'] = $this->_boost;
        }

        return $_ret;
    }
}