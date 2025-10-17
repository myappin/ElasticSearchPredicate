<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 23. 5. 2016
 * Time: 13:48
 */

namespace ElasticSearchPredicate\Endpoint;

use ElasticSearchPredicate\Endpoint\Fields\FieldsInterface;
use ElasticSearchPredicate\Endpoint\Fields\FieldsTrait;
use ElasticSearchPredicate\Endpoint\Query\QueryInterface;
use ElasticSearchPredicate\Endpoint\Query\QueryTrait;
use ElasticSearchPredicate\Predicate\HasChildPredicateSet;
use ElasticSearchPredicate\Predicate\HasParentPredicateSet;
use ElasticSearchPredicate\Predicate\NestedPredicateSet;
use ElasticSearchPredicate\Predicate\NotPredicateSet;
use ElasticSearchPredicate\Predicate\PredicateSet;
use Exception;
use OpenSearch\Client;

/**
 * Class Search
 * @package   ElasticSearchPredicate\Endpoint
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @property PredicateSet predicate
 * @method PredicateSet Fuzzy(string $term, bool|float|int|string $value, array $options = [])
 * @method PredicateSet Term(string $term, bool|float|int|string $value, array $options = [])
 * @method PredicateSet Terms(string $term, array $values, array $options = [])
 * @method PredicateSet Match(string $match, bool|float|int|string $query, array $options = [])
 * @method PredicateSet MatchPhrase(string $match, bool|float|int|string $query, array $options = [])
 * @method PredicateSet Range(string $term, int|float|null $from, int|float|null $to = null, array $options = [])
 * @method PredicateSet QueryString(bool|float|int|string $query, array $fields = [], array $options = [])
 * @method PredicateSet Exists(string $term, array $options = [])
 * @method PredicateSet Missing(string $term, array $options = [])
 * @method PredicateSet Script(array $script)
 * @method PredicateSet nest()
 * @method NotPredicateSet not()
 * @method NestedPredicateSet nested(string $path)
 * @method HasParentPredicateSet parent(string $type)
 * @method HasChildPredicateSet child(string $type)
 * @property PredicateSet AND
 * @property PredicateSet and
 * @property PredicateSet OR
 * @property PredicateSet or
 */
class Search implements EndpointInterface, QueryInterface, FieldsInterface {
    
    
    use QueryTrait, FieldsTrait;
    
    /**
     * @var string
     */
    protected string $_index;
    
    
    /**
     * @var Client
     */
    protected Client $_client;
    
    
    /**
     * @var array
     */
    protected array $_prepared_params;
    
    
    /**
     * @var bool
     */
    protected bool $_is_prepared = false;
    
    
    /**
     * @var int|null
     */
    protected ?int $_limit = null;
    
    
    /**
     * @var int|null
     */
    protected ?int $_offset = null;
    
    
    /**
     * @var array
     */
    protected array $_order = [];
    
    
    /**
     * @var array
     */
    protected array $_aggs = [];
    
    
    /**
     * @var string|null
     */
    protected ?string $_preference = null;
    
    
    /**
     * SearchPredicate constructor.
     * @param Client $client
     * @param string $index
     */
    public function __construct(Client $client, string $index) {
        $this->_client = $client;
        $this->_index = $index;
    }
    
    
    /**
     * @param $name
     * @param $arguments
     * @return PredicateSet
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function __call($name, $arguments): PredicateSet {
        if (empty($arguments)) {
            return $this->getPredicate()->$name();
        }
        
        return $this->getPredicate()->$name(...$arguments);
    }
    
    
    /**
     * @param $name
     * @return PredicateSet
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function __get($name): PredicateSet {
        $_name = strtolower($name);
        if ($_name === 'predicate' || $_name === 'predicates') {
            return $this->getPredicate();
        }
        
        return $this->getPredicate()->{$name};
    }
    
    /**
     * @param array $aggs
     * @return Search
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    public function aggs(array $aggs): self {
        $this->_aggs = $aggs;
        
        $this->clearParams();
        
        return $this;
    }
    
    /**
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function clearParams(): self {
        $this->_prepared_params = [];
        $this->_is_prepared = false;
        
        return $this;
    }
    
    /**
     * @return array
     * @throws EndpointException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function execute(): array {
        try {
            $_result = $this->_client->search($this->getPreparedParams());
        } catch (Exception $e) {
            $this->clearParams();
            
            throw $e;
        }
        
        $this->clearParams();
        
        return $_result;
    }
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getAggs(): array {
        return $this->_aggs;
    }
    
    /**
     * @return string
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getIndex(): string {
        return $this->_index;
    }
    
    /**
     * @param string $index
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setIndex(string $index): Search {
        $this->_index = $index;
        
        $this->clearParams();
        
        return $this;
    }
    
    /**
     * @return int|null
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getLimit(): ?int {
        return $this->_limit;
    }
    
    /**
     * @return int|null
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getOffset(): ?int {
        return $this->_offset;
    }
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getOrder(): array {
        return $this->_order;
    }
    
    /**
     * @return string|null
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getPreference(): ?string {
        return $this->_preference;
    }
    
    /**
     * @param string|null $preference
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setPreference(?string $preference): Search {
        $this->_preference = $preference;
        
        $this->clearParams();
        
        return $this;
    }
    
    /**
     * @return array
     * @throws EndpointException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getPreparedParams(): array {
        if (!$this->_is_prepared) {
            $this->prepareParams();
        }
        
        return $this->_prepared_params;
    }
    
    /**
     * @param int $limit
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function limit(int $limit): self {
        $this->_limit = $limit;
        
        $this->clearParams();
        
        return $this;
    }
    
    /**
     * @param int $offset
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function offset(int $offset): self {
        $this->_offset = $offset;
        
        return $this;
    }
    
    
    /**
     * @param string $term
     * @param string $asc
     * @return Search
     * @throws EndpointException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function order(string $term, string $asc): self {
        $asc = strtolower($asc);
        if (!in_array($asc, [
            'asc',
            'desc',
        ], true)
        ) {
            throw new EndpointException('Order type must be asc or desc');
        }
        
        $this->_order[] = [$term => $asc];
        
        $this->clearParams();
        
        return $this;
    }
    
    
    /**
     * @return PredicateSet
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    public function predicate(): PredicateSet {
        return $this->getPredicate();
    }
    
    
    /**
     * @return Search
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function resetOrder(): self {
        $this->_order = [];
        
        $this->clearParams();
        
        return $this;
    }
    
    
    /**
     * @throws EndpointException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    private function prepareParams(): void {
        $_prepared_params = [];
        
        if (!empty($this->_index)) {
            $_prepared_params['index'] = $this->_index;
        }
        if (is_int($this->_limit)) {
            $_prepared_params['size'] = $this->_limit;
        }
        if (is_int($this->_offset)) {
            if (empty($this->_limit)) {
                throw new EndpointException('Offset must be used with limit');
            }
            $_prepared_params['from'] = $this->_limit * $this->_offset;
        }
        if (!empty($this->_preference)) {
            $_prepared_params['preference'] = $this->_preference;
        }
        
        $_prepared_params['body'] = [];
        
        if (!empty($_fields = $this->getFields())) {
            $_prepared_params['body']['_source'] = false;
            $_prepared_params['body']['fields'] = $_fields;
        }
        if (!empty($_query = $this->getQuery())) {
            $_prepared_params['body']['query'] = $_query;
        }
        if (!empty($this->_order)) {
            $_prepared_params['body']['sort'] = $this->_order;
        }
        if (!empty($this->_aggs)) {
            $_prepared_params['body']['aggs'] = $this->_aggs;
        }
        
        $this->_prepared_params = $_prepared_params;
        $this->_is_prepared = true;
    }
    
    
}
