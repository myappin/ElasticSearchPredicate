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

use ElasticSearchPredicate\Predicate\PredicateSet\InnerHitsInterface;
use ElasticSearchPredicate\Predicate\PredicateSet\InnerHitsTrait;


/**
 * Class HasChildPredicateSet
 * nested mappings
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class HasChildPredicateSet extends PredicateSet implements InnerHitsInterface {


    use InnerHitsTrait;


    /**
     * @var
     */
    protected $_type;


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @param string $type
     * @return \ElasticSearchPredicate\Predicate\HasChildPredicateSet
     */
    public function setType(string $type) : HasChildPredicateSet {
        $this->_type = $type;

        return $this;
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @return array
     */
    public function toArray() : array {
        $_ret = [
            'has_child' => [
                'type' => $this->_type,
            ],
        ];

        if (!empty($_query = parent::toArray())) {
            $_ret['has_child']['query'] = $_query;
        }

        if ($this->_inner_hits) {
            $_ret['has_child']['inner_hits'] = $this->_inner_hits->toArray();
        }

        return $_ret;
    }


}
