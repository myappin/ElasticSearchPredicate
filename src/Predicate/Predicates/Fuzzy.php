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
 * Class Fuzzy
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Fuzzy extends AbstractPredicate implements BoostInterface, SimpleInterface {


    use BoostTrait, SimpleTrait;


    /**
     * @var string
     */
    protected $_term;


    /**
     * @var bool|float|int|string
     */
    protected $_value;


    /**
     * @var int
     */
    protected $_fuzziness;


    /**
     * @var int
     */
    protected $_prefix_length;


    /**
     * @var int
     */
    protected $_max_expansions;


    /**
     * @var array
     */
    protected $_other_options = [
        'fuzziness',
        'prefix_length',
        'max_expansions',
    ];


    /**
     * Term constructor.
     * @param string                $term
     * @param bool|float|int|string $value
     * @param array                 $options
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     */
    public function __construct(string $term, $value, array $options = []) {
        $this->_term = $term;

        if (!is_scalar($value) && $value !== null) {
            throw new PredicateException('Term value must be scalar');
        }

        $this->_value = $value;

        $this->configure($options);
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @param string|null $from
     * @param null|string $to
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     */
    public function fuzziness(int $fuzziness) {
        if ($fuzziness < 0 || $fuzziness > 2) {
            throw new PredicateException('Invalid fuzziness');
        }
        $this->_fuzziness = $fuzziness;
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @param string|null $from
     * @param null|string $to
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     */
    public function prefix_length(int $prefix_length) {
        if ($prefix_length < 0 || $prefix_length > 20) {
            throw new PredicateException('Invalid prefix_length');
        }
        $this->_prefix_length = $prefix_length;
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @param string|null $from
     * @param null|string $to
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     */
    public function max_expansions(int $max_expansions) {
        if ($max_expansions < 0 || $max_expansions > 100) {
            throw new PredicateException('Invalid max_expansions');
        }
        $this->_max_expansions = $max_expansions;
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @return array
     */
    public function toArray() : array {
        $_term = $this->_term;
        if ($this->_simple) {
            return [
                'fuzzy' => [
                    $_term => $this->_value,
                ],
            ];
        }

        $_ret = [
            'fuzzy' => [
                $_term => [
                    'value' => $this->_value,
                ],
            ],
        ];

        if (!empty($this->_boost)) {
            $_ret['fuzzy'][$_term]['boost'] = $this->_boost;
        }
        if (!empty($this->_fuzziness)) {
            $_ret['fuzzy'][$_term]['fuzziness'] = $this->_fuzziness;
        }
        if (!empty($this->_prefix_length)) {
            $_ret['fuzzy'][$_term]['prefix_length'] = $this->_prefix_length;
        }
        if (!empty($this->_max_expansions)) {
            $_ret['fuzzy'][$_term]['max_expansions'] = $this->_max_expansions;
        }

        return $_ret;
    }
}