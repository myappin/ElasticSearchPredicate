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

use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostInterface;
use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostTrait;
use ElasticSearchPredicate\Predicate\Predicates\Simple\SimpleInterface;
use ElasticSearchPredicate\Predicate\Predicates\Simple\SimpleTrait;
use ElasticSearchPredicate\Predicate\PredicateSet;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class Term
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Term extends AbstractPredicate implements BoostInterface, SimpleInterface {
    
    
    use BoostTrait, SimpleTrait;
    
    /**
     * @var string
     */
    protected string $_term;
    
    
    /**
     * @var bool|float|int|string
     */
    protected bool|float|int|string $_value;
    
    
    /**
     * Term constructor.
     * @param string                $term
     * @param bool|float|int|string $value
     * @param array                 $options
     */
    public function __construct(string $term, bool|float|int|string $value, array $options = []) {
        $this->_term = $term;
        $this->_value = $value;
        
        $this->configure($options);
    }
    
    
    /**
     * @return string
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getTerm(): string {
        return $this->_term;
    }
    
    
    /**
     * @param string $term
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setTerm(string $term): self {
        $this->_term = $term;
        
        return $this;
    }
    
    
    /**
     * @param string $path
     * @return self
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function pathFix(string $path): self {
        if (!empty($path)) {
            $this->_term = PredicateSet::pathFixer($path, $this->_term);
        }
        
        return $this;
    }
    
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    #[ArrayShape(['term' => "array"])]
    public function toArray(): array {
        $_term = $this->_term;
        
        if ($this->_simple) {
            return [
                'term' => [
                    $_term => $this->_value,
                ],
            ];
        }
        
        $_ret = [
            'term' => [
                $_term => [
                    'value' => $this->_value,
                ],
            ],
        ];
        
        if (!empty($this->_boost)) {
            $_ret['term'][$_term]['boost'] = $this->_boost;
        }
        
        return $_ret;
    }
}
