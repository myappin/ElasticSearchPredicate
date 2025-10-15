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
 * Time: 9:36
 */

namespace ElasticSearchPredicate\Predicate\Predicates;

use ElasticSearchPredicate\Predicate\PredicateException;
use ElasticSearchPredicate\Predicate\PredicateSet;

/**
 * Class AbstractPredicate
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
abstract class AbstractPredicate implements PredicateInterface {
    
    
    /**
     * @var string
     */
    protected string $_combiner = PredicateSet::C_AND;
    
    
    /**
     * @var array
     */
    protected array $_other_options = [];
    
    /**
     * @param array $options
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function configure(array $options): void {
        $_implements = class_implements($this);
        if (!empty($options)) {
            foreach ($options as $key => $opt) {
                if (in_array($key, $this->_other_options, true)) {
                    if (empty($opt)) {
                        $this->$key();
                    } else {
                        if (is_scalar($opt)) {
                            $opt = [$opt];
                        }
                        
                        $this->$key(...$opt);
                    }
                    continue;
                }
                $_method = strtolower($key);
                $_key = ucfirst($_method);
                
                if (!in_array(
                    'ElasticSearchPredicate\Predicate\\Predicates\\' . $_key . '\\' . $_key . 'Interface',
                    $_implements,
                    true
                )
                ) {
                    continue;
                }
                
                if (empty($opt)) {
                    $this->$_method();
                } else {
                    if (is_scalar($opt)) {
                        $opt = [$opt];
                    }
                    
                    $this->$_method(...$opt);
                }
            }
        }
    }
    
    /**
     * @return string
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getCombiner(): string {
        return $this->_combiner;
    }
    
    /**
     * @param string $combiner
     * @return $this
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setCombiner(string $combiner): self {
        if ($combiner !== PredicateSet::C_AND && $combiner !== PredicateSet::C_OR) {
            throw new PredicateException('Unsupported combiner');
        }
        
        $this->_combiner = $combiner;
        
        return $this;
    }
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    abstract public function toArray(): array;
    
    
}
