<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 9:37
 */

namespace ElasticSearchPredicate\Endpoint;

/**
 * Interface EndpointInterface
 * @package ElasticSearchPredicate\Endpoint
 */
interface EndpointInterface {
    
    
    /**
     * @return EndpointInterface
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function clearParams(): EndpointInterface;
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function execute(): array;
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getPreparedParams(): array;
    
    
}
