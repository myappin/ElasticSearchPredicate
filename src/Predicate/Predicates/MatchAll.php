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
 * Class MatchAll
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class MatchAll extends AbstractPredicate {


    /**
     * Match constructor.
     */
    public function __construct() {
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     * @return array
     */
    public function toArray() : array {
        return ['match_all' => new \stdClass()];
    }
}