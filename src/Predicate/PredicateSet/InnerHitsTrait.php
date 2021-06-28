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

use ElasticSearchPredicate\Predicate\PredicateException;
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
    protected ?InnerHits $_inner_hits = null;


    /**
     * @param string|null $name
     * @param null        $inner_hits
     * @return $this
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function innerHits(?string $name, &$inner_hits = null): self {
        if (isset($this->_inner_hits)) {
            throw new PredicateException('InnerHits lready set');
        }

        $this->_inner_hits = $inner_hits = new InnerHits($name);

        return $this;
    }


    /**
     * @return bool
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    public function hasInnerHits(): bool {
        return isset($this->_inner_hits);
    }


}
