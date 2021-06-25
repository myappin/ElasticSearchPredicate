<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 10:28
 */

namespace ElasticSearchPredicate\Predicate\FunctionScore\Weight;

/**
 * Interface WeightInterface
 * @package ElasticSearchPredicate\Predicate\FunctionScore\Weigh
 */
interface WeightInterface {


    /**
     * @param int|float $weight
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setWeight(int|float $weight): self;


}
