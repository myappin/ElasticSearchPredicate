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

namespace ElasticSearchPredicate\Endpoint\Routing;

/**
 * Interface RoutingInterface
 * @package ElasticSearchPredicate\Endpoint\Routing
 */
interface RoutingInterface {
    
    /**
     * @return string|null
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getRouting(): string|null;
    
    /**
     * @param string|null $routing
     * @return self
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setRouting(string|null $routing): self;
    
    
}
