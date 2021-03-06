<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 25. 5. 2016
 * Time: 22:36
 */

namespace ElasticSearchPredicate\Predicate\Predicates\Type;

use ElasticSearchPredicate\Predicate\PredicateException;

/**
 * Class TypeTrait
 * @package   ElasticSearchPredicate\Predicate\Predicates\Type
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait TypeTrait {


    /**
     * @var string
     */
    protected string $_type;


    /**
     * @var array
     */
    protected array $_types = [];


    /**
     * @param string $type
     * @return $this
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function type(string $type): self {
        if (!in_array($type, $this->_types, true)) {
            throw new PredicateException(sprintf('Type %s is not valid', $type));
        }

        $this->_type = $type;
        $this->_simple = false;

        return $this;
    }


}
