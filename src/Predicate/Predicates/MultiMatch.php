<?php
/*
 * *
 *  * MyAppIn (http://www.myappin.com)
 *  * @author    Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
 *  * @link      http://www.myappin.com
 *  * @copyright Copyright (c) 2025 MyAppIn s.r.o. (http://www.myappin.com)
 *
 */

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
use ElasticSearchPredicate\Predicate\Predicates\Operator\OperatorInterface;
use ElasticSearchPredicate\Predicate\Predicates\Operator\OperatorTrait;
use ElasticSearchPredicate\Predicate\Predicates\Type\TypeInterface;
use ElasticSearchPredicate\Predicate\Predicates\Type\TypeTrait;
use ElasticSearchPredicate\Predicate\PredicateSet;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class MultiMatch
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class MultiMatch extends AbstractPredicate implements TypeInterface, OperatorInterface {
    
    
    use TypeTrait, OperatorTrait;
    
    /**
     * @var array|string[]
     */
    protected array $_fields;
    
    
    /**
     * @var bool|float|int|string
     */
    protected bool|float|int|string $_query;
    
    
    /**
     * @var float|int
     */
    protected float|int $_tie_breaker;
    
    
    /**
     * @var string
     */
    protected string $_minimum_should_match;
    
    
    /**
     * MultiMatch constructor.
     * @param bool|float|int|string $query
     * @param array|string          $fields
     * @param array                 $options
     * @throws PredicateException
     */
    public function __construct(bool|float|int|string $query, array|string $fields, array $options = []) {
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
        } else if (is_string($fields)) {
            $this->_fields = [$fields];
        } else {
            throw new PredicateException('Unexpected field type');
        }
        
        $this->_query = $query;
        
        $this->_other_options = [
            'tie_breaker',
            'minimum_should_match',
        ];
        $this->_types = [
            'phrase',
            'phrase_prefix',
            'cross_fields',
            'most_fields',
            'best_fields',
        ];
        
        $this->configure($options);
    }
    
    
    /**
     * @return array|string[]
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getFields(): array {
        return $this->_fields;
    }
    
    
    /**\
     * @param array $fields
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setFields(array $fields): self {
        $this->_fields = $fields;
        
        return $this;
    }
    
    
    /**
     * @param string $minimum_should_match
     * @return $this
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function minimum_should_match(string $minimum_should_match): self {
        if (!preg_match('/^\d+%$/', $minimum_should_match)) {
            throw new PredicateException('Minimum should match must be string with percents');
        }
        
        $this->_minimum_should_match = $minimum_should_match;
        
        return $this;
    }
    
    
    /**
     * @param string $path
     * @return $this
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
     * @param int|float $tie_breaker
     * @return $this
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function tie_breaker(int|float $tie_breaker): self {
        if ($tie_breaker < 0 || $tie_breaker > 1) {
            throw new PredicateException('Tie breaker must be between 0 and 1');
        }
        
        $this->_tie_breaker = $tie_breaker;
        
        return $this;
    }
    
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    #[ArrayShape(['multi_match' => "array"])]
    public function toArray(): array {
        $_ret = [
            'multi_match' => [
                'query' => $this->_query,
            ],
        ];
        
        if (!empty($this->_fields)) {
            $_ret['multi_match']['fields'] = $this->_fields;
        }
        
        if (!empty($this->_type)) {
            $_ret['multi_match']['type'] = $this->_type;
        }
        
        if (!empty($this->_operator)) {
            $_ret['multi_match']['operator'] = $this->_operator;
        }
        
        if (!empty($this->_tie_breaker)) {
            $_ret['multi_match']['tie_breaker'] = $this->_tie_breaker;
        }
        
        if (!empty($this->_minimum_should_match)) {
            $_ret['multi_match']['minimum_should_match'] = $this->_minimum_should_match;
        }
        
        return $_ret;
    }
}
