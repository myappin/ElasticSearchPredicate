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

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use stdClass;

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
     * @return array
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    #[ArrayShape(['match_all' => stdClass::class])]
    #[Pure]
    public function toArray(): array {
        return ['match_all' => new stdClass()];
    }
}
