<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 13:19
 */

namespace ElasticSearchPredicate\Predicate\FunctionScore\Weight;

use ElasticSearchPredicate\Predicate\FunctionScore\AbstractFunction;

/**
 * Class WeightTrait
 * @package   ElasticSearchPredicate\Predicate\FunctionScore\Weigh
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait WeightTrait {
    
    
    /**
     * @var int|float|null
     */
    protected int|float|null $_weight;
    
    
    /**
     * @param int|float|null $weight
     * @return AbstractFunction|WeightTrait
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setWeight(int|float|null $weight): self {
        $this->_weight = $weight;
        
        return $this;
    }
    
    
}
