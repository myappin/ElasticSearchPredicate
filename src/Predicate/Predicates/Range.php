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
use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostInterface;
use ElasticSearchPredicate\Predicate\Predicates\Boost\BoostTrait;
use ElasticSearchPredicate\Predicate\Predicates\Simple\SimpleTrait;
use ElasticSearchPredicate\Predicate\PredicateSet;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class Range
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Range extends AbstractPredicate implements BoostInterface {
    
    
    use BoostTrait, SimpleTrait;
    
    /**
     * @var string
     */
    protected string $_term;
    
    
    /**
     * @var int|float|string|null
     */
    protected int|float|string|null $_from;
    
    
    /**
     * @var int|float|string|null
     */
    protected int|float|string|null $_to;
    
    
    /**
     * @var string
     */
    protected string $_from_type = 'gte';
    
    
    /**
     * @var string
     */
    protected string $_to_type = 'lte';
    
    
    /**
     * @var string
     */
    protected string $_format;
    
    
    /**
     * @var array
     */
    protected array $_other_options = ['types'];
    
    
    /**
     * Range constructor.
     * @param string                $term
     * @param int|float|string|null $from
     * @param int|float|string|null $to
     * @param array                 $options
     * @throws PredicateException
     */
    public function __construct(string $term, int|float|string|null $from, int|float|string|null $to = null, array $options = []) {
        $this->_term = $term;
        
        if ($from === null && $to === null) {
            throw new PredicateException('Both of from and to can not be null');
        }
        
        $this->_from = $from;
        $this->_to = $to;
        
        $this->configure($options);
    }
    
    /**
     * @param string $format
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function format(string $format): void {
        $this->_format = $format;
        $this->_simple = false;
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
    #[ArrayShape(['range' => "array"])]
    public function toArray(): array {
        $_term = $this->_term;
        
        $_ret = [
            'range' => [
                $_term => [],
            ],
        ];
        
        if ($this->_from !== null) {
            $_ret['range'][$_term][$this->_from_type] = $this->_from;
        }
        
        if ($this->_to !== null) {
            $_ret['range'][$_term][$this->_to_type] = $this->_to;
        }
        
        if (!empty($this->_boost)) {
            $_ret['range'][$_term]['boost'] = $this->_boost;
        }
        
        if (!empty($this->_format)) {
            $_ret['range'][$_term]['format'] = $this->_format;
        }
        
        return $_ret;
    }
    
    
    /**
     * @param string|null $from
     * @param string|null $to
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function types(?string $from, ?string $to): void {
        if ($from !== null) {
            if (!in_array($from, [
                'gte',
                'gt',
            ], true)
            ) {
                throw new PredicateException('From type can be one of gt and gte');
            }
            
            $this->_from_type = $from;
        }
        if ($to !== null) {
            if (!in_array($to, [
                'lte',
                'lt',
            ], true)
            ) {
                throw new PredicateException('From type can be one of gt and gte');
            }
            
            $this->_to_type = $to;
        }
    }
}
