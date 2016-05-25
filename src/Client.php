<?php
declare(strict_types = 1);
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
use ElasticSearchPredicate\Endpoint\EndpointException;
use ElasticSearchPredicate\Endpoint\Search;

/**
 * Class Client
 * @package   ElasticSearchPredicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Client {


	/**
	 * @var ClientBuilder
	 */
	protected $_elasticsearch_builder;


	/**
	 * @var \Elasticsearch\Client
	 */
	protected $_elasticsearch;


	/**
	 * Client constructor.
	 */
	public function __construct(){
		$this->_elasticsearch_builder = ClientBuilder::create();
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \Elasticsearch\ClientBuilder
	 * @throws \ElasticSearchPredicate\Endpoint\EndpointException
	 */
	public function getClientBuilder() : ClientBuilder{
		if($this->_elasticsearch_builder === null){
			throw new EndpointException('ElasticSearch client is already built.');
		}

		return $this->_elasticsearch_builder;
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @param string $index
	 * @param string $type
	 * @return \ElasticSearchPredicate\Endpoint\Search
	 */
	public function search(string $index = '', string $type = '') : Search{
		return new Search($this->getElasticSearchClient(), $index, $type);
	}


	/**
	 * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
	 * @return \Elasticsearch\Client
	 */
	public function getElasticSearchClient(){
		if($this->_elasticsearch instanceof \Elasticsearch\Client){
			return $this->_elasticsearch;
		}

		$this->_elasticsearch         = $this->_elasticsearch_builder->build();
		$this->_elasticsearch_builder = null;

		return $this->_elasticsearch;
	}


}