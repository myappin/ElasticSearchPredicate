<?php
declare(strict_types = 1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 27. 5. 2016
 * Time: 16:51
 */

namespace ElasticSearchPredicate\Predicate;

use ElasticSearchPredicate\Predicate\PredicateSet\InnerHitsInterface;
use ElasticSearchPredicate\Predicate\PredicateSet\InnerHitsTrait;


/**
 * Class NestedPredicateSet
 * nested mappings
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class NestedPredicateSet extends PredicateSet implements InnerHitsInterface {


    use InnerHitsTrait;


    /**
     * @var
     */
    protected $_path;


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @param string $path
     * @return \ElasticSearchPredicate\Predicate\NestedPredicateSet
     */
    public function setPath(string $path) : NestedPredicateSet {
        $this->_path = $path;

        return $this;
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @return array
     */
    public function toArray() : array {
        $_ret = [
            'nested' => [
                'path' => $this->_path,
            ],
        ];

        if (!empty($_query = parent::toArray())) {
            $_ret['nested']['query'] = $_query;
        }

        if ($this->_inner_hits) {
            $_ret['nested']['inner_hits'] = $this->_inner_hits->toArray();
        }

        return $_ret;
    }


}