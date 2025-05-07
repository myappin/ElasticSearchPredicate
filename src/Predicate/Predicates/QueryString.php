<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 10:00
 */

namespace ElasticSearchPredicate\Predicate\Predicates;

use ElasticSearchPredicate\Predicate\PredicateException;
use ElasticSearchPredicate\Predicate\Predicates\Analyzer\AnalyzerInterface;
use ElasticSearchPredicate\Predicate\Predicates\Analyzer\AnalyzerTrait;
use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostInterface;
use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostTrait;
use ElasticSearchPredicate\Predicate\PredicateSet;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class QueryString
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class QueryString extends AbstractPredicate implements BoostInterface, AnalyzerInterface {


    use BoostTrait, AnalyzerTrait;

    /**
     * @var bool|float|int|string
     */
    protected bool|float|int|string $_query;


    /**
     * @var array
     */
    protected array $_fields = [];


    /**
     * QueryString constructor.
     * @param bool|float|int|string $query
     * @param array|string          $fields
     * @param array                 $options
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     */
    public function __construct(bool|float|int|string $query, array|string $fields = [], array $options = []) {
        if (!is_scalar($query)) {
            throw new PredicateException('Query must be scalar');
        }

        $this->_query = $query;

        if (empty($fields)) {
            throw new PredicateException('Fields can not be empty set');
        }

        if (is_array($fields)) {
            foreach ($fields as $field) {
                if (!is_scalar($field)) {
                    throw new PredicateException('Filed must be scalar');
                }
            }

            $this->_fields = $fields;
        }
        else if (is_string($fields)) {
            $this->_fields = [$fields];
        }
        else {
            throw new PredicateException('Unexpected field type');
        }

        $this->configure($options);
    }


    /**
     * @return array|string[]
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getFields(): array {
        return $this->_fields;
    }


    /**
     * @param array $fields
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setFields(array $fields): self {
        $this->_fields = $fields;

        return $this;
    }


    /**
     * @param string $path
     * @return self
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function pathFix(string $path): self {
        if (!empty($path)) {
            foreach ($this->_fields as $key => $field) {
                $this->_fields[$key] = PredicateSet::pathFixer($path, $field);
            }
        }

        return $this;
    }


    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    #[ArrayShape(['query_string' => "array"])]
    public function toArray(): array {
        $_ret = [
            'query_string' => [
                'query' => $this->_query,
            ],
        ];

        if (!empty($this->_fields)) {
            $_ret['query_string']['fields'] = $this->_fields;
        }

        if (!empty($this->_boost)) {
            $_ret['query_string']['boost'] = $this->_boost;
        }

        if (!empty($this->_analyzer)) {
            $_ret['query_string']['analyzer'] = $this->_analyzer;
        }

        return $_ret;
    }
}
