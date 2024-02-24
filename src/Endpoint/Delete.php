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

use Elasticsearch\Client;
use ElasticSearchPredicate\Endpoint\Query\QueryInterface;
use ElasticSearchPredicate\Endpoint\Query\QueryTrait;
use ElasticSearchPredicate\Predicate\HasChildPredicateSet;
use ElasticSearchPredicate\Predicate\HasParentPredicateSet;
use ElasticSearchPredicate\Predicate\NestedPredicateSet;
use ElasticSearchPredicate\Predicate\NotPredicateSet;
use ElasticSearchPredicate\Predicate\PredicateSet;
use Exception;

/**
 * Class Delete
 * @package   ElasticSearchPredicate\Endpoint
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @property PredicateSet predicate
 * @method PredicateSet Term(string $term, bool|float|int|string $value, array $options = [])
 * @method PredicateSet Terms(string $term, array $values, array $options = [])
 * @method PredicateSet Match(string $match, bool|float|int|string $query, array $options = [])
 * @method PredicateSet Range(string $term, int|float|null $from, int|float|null $to = null, array $options = [])
 * @method PredicateSet QueryString(bool|float|int|string $query, array $fields = [], array $options = [])
 * @method PredicateSet Exists(string $term, array $options = [])
 * @method PredicateSet Missing(string $term, array $options = [])
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
class Delete implements EndpointInterface, QueryInterface {


    use QueryTrait;

    /**
     * @var string|array
     */
    protected string|array $_index;


    /**
     * @var string
     */
    protected string $_type;


    /**
     * @var \Elasticsearch\Client
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
     * @param \Elasticsearch\Client $client
     * @param string|array          $index
     * @param string                $type
     */
    public function __construct(Client $client, string|array $index, string $type) {
        $this->_client = $client;
        $this->_index = $index;
        $this->_type = $type;
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
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
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
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getPreparedParams(): array {
        if (!$this->_is_prepared) {
            $this->prepareParams();
        }

        return $this->_prepared_params;
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
     * @param bool $refresh
     * @param bool $wait
     * @return array
     * @throws \Exception
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function execute(
        bool $refresh = true,
        bool $wait = true
    ): array {
        try {
            $_params = $this->getPreparedParams();

            $_params['refresh'] = $refresh ? 'true' : 'false';
            $_params['wait_for_completion'] = $wait ? 'true' : 'false';

            if (isset($_params['index'])) {
                if (is_scalar($_params['index'])) {
                    $_params['index'] = [
                        [
                            'index'             => $_params['index'],
                            'wait_for_response' => true,
                        ],
                    ];
                }

                foreach ($_params['index'] as $_index) {
                    $_result[] = $this->_client->deleteByQuery(
                        array_merge(
                            $_params,
                            ['index' => $_index],
                            $_index['wait_for_response'] ? [] : [
                                'client' => [
                                    'curl' => [
                                        CURLOPT_RETURNTRANSFER => 0,
                                        CURLOPT_TIMEOUT_MS     => 1,
                                    ],
                                    'headers' => [
                                        'Connection' => 'close',
                                    ],
                                ],
                            ]
                        )
                    );
                }
            }
            else {
                $_result = [$this->_client->deleteByQuery($_params)];
            }
        }
        catch (Exception $e) {
            $this->clearParams();

            throw $e;
        }

        $this->clearParams();

        return $_result;
    }


    /**
     * @return \ElasticSearchPredicate\Predicate\PredicateSet
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    public function predicate(): PredicateSet {
        return $this->getPredicate();
    }


    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    private function prepareParams(): void {
        $_prepared_params = [];

        if (!empty($this->_index)) {
            $_prepared_params['index'] = $this->_index;
        }
        if (!empty($this->_type)) {
            $_prepared_params['type'] = $this->_type;
        }

        $_prepared_params['conflicts'] = 'proceed';

        $_prepared_params['body'] = [];

        if (!empty($_query = $this->getQuery())) {
            $_prepared_params['body']['query'] = $_query;
        }

        $this->_prepared_params = $_prepared_params;
        $this->_is_prepared = true;
    }


}
