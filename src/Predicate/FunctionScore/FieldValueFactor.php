<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 17. 6. 2016
 * Time: 10:51
 */

namespace ElasticSearchPredicate\Predicate\FunctionScore;

use ElasticSearchPredicate\Predicate\PredicateException;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class FieldValueFactor
 * @package   ElasticSearchPredicate\Predicate\FunctionScore
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class FieldValueFactor extends AbstractFunction {
    
    
    /**
     * @var string
     */
    protected string $_field;
    
    
    /**
     * @var float|int|null
     */
    protected float|int|null $_factor;
    
    
    /**
     * @var string|null
     */
    protected ?string $_modifier = null;
    
    
    /**
     * @var float|int|null
     */
    protected float|int|null $_missing;
    
    
    /**
     * FieldValueFactor constructor.
     * @param string         $field
     * @param float|int|null $factor
     * @param string|null    $modifier
     * @param float|int|null $missing
     * @throws PredicateException
     */
    public function __construct(string $field, float|int|null $factor = null, string $modifier = null, float|int|null $missing = null) {
        $this->setField($field);
        
        if ($factor !== null) {
            $this->setFactor($factor);
        }
        if ($modifier !== null) {
            $this->setModifier($modifier);
        }
        if ($missing !== null) {
            $this->setMissing($missing);
        }
    }
    
    
    /**
     * @return int|float|null
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getFactor(): int|float|null {
        return $this->_factor;
    }
    
    
    /**
     * @param int|float|null $factor
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setFactor(int|float|null $factor): self {
        $this->_factor = $factor;
        
        return $this;
    }
    
    
    /**
     * @return string
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getField(): string {
        return $this->_field;
    }
    
    
    /**
     * @param string $field
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setField(string $field): self {
        $this->_field = $field;
        
        return $this;
    }
    
    
    /**
     * @return int|float|null
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getMissing(): int|float|null {
        return $this->_missing;
    }
    
    
    /**
     * @param int|float|null $missing
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setMissing(int|float|null $missing): self {
        $this->_missing = $missing;
        
        return $this;
    }
    
    
    /**
     * @return string
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getModifier(): string {
        return $this->_modifier;
    }
    
    
    /**
     * @param string|null $modifier
     * @return $this
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setModifier(?string $modifier): self {
        if (!in_array($modifier, [
            'none',
            'log',
            'log1p',
            'log2p',
            'ln',
            'ln1p',
            'ln2p',
            'square',
            'sqrt',
            'reciprocal',
            null,
        ], true)
        ) {
            throw new PredicateException('Modifier is not supported');
        }
        
        $this->_modifier = $modifier;
        
        return $this;
    }
    
    
    /**
     * @return array
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    #[ArrayShape([
        'field_value_factor' => "array",
        'weight'             => "int|float",
        'filter'             => "array",
    ])]
    public function toArray(): array {
        $_ret = [
            'field_value_factor' => [
                'field' => $this->_field,
            ],
        ];
        
        if (isset($this->_factor)) {
            $_ret['field_value_factor']['factor'] = $this->_factor;
        }
        if (!empty($this->_modifier)) {
            $_ret['field_value_factor']['modifier'] = $this->_modifier;
        }
        if (!empty($this->_missing)) {
            $_ret['field_value_factor']['missing'] = $this->_missing;
        }
        
        if (!empty($_query = $this->getQuery())) {
            $_ret['filter'] = $_query;
        }
        
        if (!empty($this->_weight)) {
            $_ret['weight'] = $this->_weight;
        }
        
        return $_ret;
    }
    
    
}
