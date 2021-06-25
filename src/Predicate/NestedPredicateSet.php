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
 * Class NestedPredicateSet
 * nested mappings
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class NestedPredicateSet extends PredicateSet implements InnerHitsInterface {


    use InnerHitsTrait;

    /**
     * @var string
     */
    protected string $_path;


    /**
     * @param string $path
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setPath(string $path): self {
        $this->_path = $path;

        return $this;
    }


    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    #[ArrayShape(['nested' => "array"])]
    public function toArray(): array {
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
