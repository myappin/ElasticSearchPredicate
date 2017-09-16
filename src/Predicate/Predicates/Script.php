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


/**
 * Class Term
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Script extends AbstractPredicate {


    /**
     * @var array
     */
    protected $_script;


    /**
     * Term constructor.
     * @param string                $term
     * @param bool|float|int|string $value
     * @param array                 $options
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     */
    public function __construct(array $script) {
        $this->_script = $script;
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @return array
     */
    public function toArray() : array {
        $_ret = [
            'script' => [
                'script' => $this->_script,
            ],
        ];

        return $_ret;
    }
}