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
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class HasParentPredicateSet
 * nested mappings
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class HasParentPredicateSet extends PredicateSet implements InnerHitsInterface {


    use InnerHitsTrait;

    /**
     * @var string
     */
    protected string $_type;


    /**
     * @param string $type
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setType(string $type): self {
        $this->_type = $type;

        return $this;
    }


    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    #[ArrayShape(['has_parent' => "string[]"])]
    public function toArray(): array {
        $_ret = [
            'has_parent' => [
                'parent_type' => $this->_type,
            ],
        ];

        if (!empty($_query = parent::toArray())) {
            $_ret['has_parent']['query'] = $_query;
        }

        if ($this->_inner_hits) {
            $_ret['has_parent']['inner_hits'] = $this->_inner_hits->toArray();
        }

        return $_ret;
    }


}
