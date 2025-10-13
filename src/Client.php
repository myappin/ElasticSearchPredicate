<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 23. 5. 2016
 * Time: 13:31
 */

namespace ElasticSearchPredicate;


use ElasticSearchPredicate\Endpoint\Count;
use ElasticSearchPredicate\Endpoint\Delete;
use ElasticSearchPredicate\Endpoint\Search;
use ElasticSearchPredicate\Endpoint\Update;

/**
 * Class Client
 * @package   ElasticSearchPredicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Client {
    
    
    /**
     * @var \OpenSearch\Client
     */
    protected \OpenSearch\Client $_elasticsearch;
    
    /**
     * @param string $index
     * @return Count
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function count(string $index): Count {
        return new Count($this->getElasticSearchClient(), $index);
    }
    
    /**
     * @param string|array $index
     * @return Delete
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function delete(string|array $index): Delete {
        return new Delete($this->getElasticSearchClient(), $index);
    }
    
    /**
     * @return \OpenSearch\Client
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getElasticSearchClient(): \OpenSearch\Client {
        return $this->_elasticsearch;
    }
    
    /**
     * @param string $index
     * @return Search
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function search(string $index): Search {
        return new Search($this->getElasticSearchClient(), $index);
    }
    
    /**
     * @param \OpenSearch\Client $elasticsearch
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setClient(\OpenSearch\Client $elasticsearch): void {
        $this->_elasticsearch = $elasticsearch;
    }
    
    /**
     * @param string|array $index
     * @param string       $script
     * @param array        $params
     * @return Update
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function update(string|array $index, string $script, array $params = []): Update {
        return new Update($this->getElasticSearchClient(), $index, $script, $params);
    }
    
    
}
