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

use ElasticSearchPredicate\Predicate\PredicateSet;


/**
 * Class InnerHitsTrait
 * @package   ElasticSearchPredicate\Predicate\PredicateSet
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @property PredicateSet $this
 */
trait InnerHitsTrait {


    /**
     * @var null|InnerHits
     */
    protected $_inner_hits = null;


    /**
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     * @param string|null $name
     * @param null        $inner_hits
     * @return $this
     */
    public function innerHits(string $name = null, &$inner_hits = null) {
        if (!$this->_inner_hits) {
            $this->_inner_hits = $inner_hits = new InnerHits($name);
        }

        return $this;
    }


    /**
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     * @return bool
     */
    public function hasInnerHits() : bool {
        return $this->_inner_hits !== null;
    }


}