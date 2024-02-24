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

use Elasticsearch\ClientBuilder;
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
     * @var \Elasticsearch\ClientBuilder|null
     */
    protected ?ClientBuilder $_elasticsearch_builder;


    /**
     * @var \Elasticsearch\Client
     */
    protected \Elasticsearch\Client $_elasticsearch;


    /**
     * Client constructor.
     */
    public function __construct() {
        $this->_elasticsearch_builder = ClientBuilder::create();
    }


    /**
     * @return \Elasticsearch\ClientBuilder
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
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
     * @return \Elasticsearch\Client
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getElasticSearchClient(): \Elasticsearch\Client {
        if (isset($this->_elasticsearch)) {
            return $this->_elasticsearch;
        }

        $this->_elasticsearch = $this->_elasticsearch_builder->build();

        $this->_elasticsearch_builder = null;

        return $this->_elasticsearch;
    }


    /**
     * @param string $index
     * @param string $type
     * @return \ElasticSearchPredicate\Endpoint\Search
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function search(string $index, string $type): Search {
        return new Search($this->getElasticSearchClient(), $index, $type);
    }


    /**
     * @param string $index
     * @param string $type
     * @return \ElasticSearchPredicate\Endpoint\Count
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function count(string $index, string $type): Count {
        return new Count($this->getElasticSearchClient(), $index, $type);
    }


    /**
     * @param string|array $index
     * @param string $type
     * @return \ElasticSearchPredicate\Endpoint\Delete
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function delete(string|array $index, string $type): Delete {
        return new Delete($this->getElasticSearchClient(), $index, $type);
    }


    /**
     * @param string|array $index
     * @param string $type
     * @param string $script
     * @param array  $params
     * @return \ElasticSearchPredicate\Endpoint\Update
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    public function update(string|array $index, string $type, string $script, array $params = []): Update {
        return new Update($this->getElasticSearchClient(), $index, $type, $script, $params);
    }


}
