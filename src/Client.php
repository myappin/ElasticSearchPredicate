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

use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use ElasticSearchPredicate\Endpoint\Count;
use ElasticSearchPredicate\Endpoint\Delete;
use ElasticSearchPredicate\Endpoint\EndpointException;
use ElasticSearchPredicate\Endpoint\Search;
use ElasticSearchPredicate\Endpoint\Update;

/**
 * Class Client
 * @package   ElasticSearchPredicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Client {
    
    
    /**
     * @var ClientBuilder|null
     */
    protected ?ClientBuilder $_elasticsearch_builder;
    
    
    /**
     * @var \Elastic\Elasticsearch\Client
     */
    protected \Elastic\Elasticsearch\Client $_elasticsearch;
    
    
    /**
     * Client constructor.
     */
    public function __construct() {
        $this->_elasticsearch_builder = ClientBuilder::create();
    }
    
    /**
     * @param string $index
     * @return Count
     * @throws AuthenticationException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function count(string $index): Count {
        return new Count($this->getElasticSearchClient(), $index);
    }
    
    /**
     * @param string|array $index
     * @return Delete
     * @throws AuthenticationException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function delete(string|array $index): Delete {
        return new Delete($this->getElasticSearchClient(), $index);
    }
    
    /**
     * @return ClientBuilder
     * @throws EndpointException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getClientBuilder(): ClientBuilder {
        if (
            empty($this->_elasticsearch_builder)
            || isset($this->_elasticsearch)
        ) {
            throw new EndpointException('ElasticSearch client is already built.');
        }
        
        return $this->_elasticsearch_builder;
    }
    
    /**
     * @return \Elastic\Elasticsearch\Client
     * @throws AuthenticationException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getElasticSearchClient(): \Elastic\Elasticsearch\Client {
        if (isset($this->_elasticsearch)) {
            return $this->_elasticsearch;
        }
        
        $this->_elasticsearch = $this->_elasticsearch_builder->build();
        
        $this->_elasticsearch_builder = null;
        
        return $this->_elasticsearch;
    }
    
    /**
     * @param string $index
     * @return Search
     * @throws AuthenticationException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function search(string $index): Search {
        return new Search($this->getElasticSearchClient(), $index);
    }
    
    
    /**
     * @param string|array $index
     * @param string       $script
     * @param array        $params
     * @return Update
     * @throws AuthenticationException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function update(string|array $index, string $script, array $params = []): Update {
        return new Update($this->getElasticSearchClient(), $index, $script, $params);
    }
    
    
}
