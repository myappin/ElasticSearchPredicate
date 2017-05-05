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


/**
 * Class HasParentPredicateSet
 * nested mappings
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class HasParentPredicateSet extends PredicateSet {


    /**
     * @var
     */
    protected $_type;


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @param string $type
     * @return \ElasticSearchPredicate\Predicate\HasParentPredicateSet
     */
    public function setType(string $type) : HasParentPredicateSet {
        $this->_type = $type;

        return $this;
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @return array
     */
    public function toArray() : array {
        return [
            'has_parent' => [
                'parent_type' => $this->_type,
                'query'       => parent::toArray(),
            ],
        ];
    }


}