<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 17. 6. 2016
 * Time: 9:58
 */

namespace ElasticSearchPredicate\Predicate\FunctionScore;

use ElasticSearchPredicate\Endpoint\Query\QueryInterface;
use ElasticSearchPredicate\Predicate\FunctionScore\Weight\WeightInterface;

/**
 * Interface FunctionInterface
 * @package   ElasticSearchPredicate\Predicate\FunctionScore
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
interface FunctionInterface extends QueryInterface, WeightInterface {


    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function toArray(): array;


}
