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
use ElasticSearchPredicate\Predicate\Predicates\Simple\SimpleInterface;
use ElasticSearchPredicate\Predicate\Predicates\Simple\SimpleTrait;
use ElasticSearchPredicate\Predicate\PredicateSet;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class Fuzzy
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Fuzzy extends AbstractPredicate implements BoostInterface, SimpleInterface {
    
    
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
     * @var int|float|string
     */
    protected int|float|string $_fuzziness;
    
    
    /**
     * @var int
     */
    protected int $_prefix_length;
    
    
    /**
     * @var int
     */
    protected int $_max_expansions;
    
    
    /**
     * @var array
     */
    protected array $_other_options = [
        'fuzziness',
        'prefix_length',
        'max_expansions',
    ];
    
    
    /**
     * Fuzzy constructor.
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
     * @param int|float|string $fuzziness
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function fuzziness(int|float|string $fuzziness): void {
        $this->_fuzziness = $fuzziness;
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
     * @param int $max_expansions
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function max_expansions(int $max_expansions): void {
        if ($max_expansions < 0 || $max_expansions > 100) {
            throw new PredicateException('Invalid max_expansions');
        }
        
        $this->_max_expansions = $max_expansions;
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
     * @param int $prefix_length
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function prefix_length(int $prefix_length): void {
        if ($prefix_length < 0 || $prefix_length > 20) {
            throw new PredicateException('Invalid prefix_length');
        }
        
        $this->_prefix_length = $prefix_length;
    }
    
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    #[ArrayShape(['fuzzy' => "array"])]
    public function toArray(): array {
        $_term = $this->_term;
        
        if ($this->_simple) {
            return [
                'fuzzy' => [
                    $_term => $this->_value,
                ],
            ];
        }
        
        $_ret = [
            'fuzzy' => [
                $_term => [
                    'value' => $this->_value,
                ],
            ],
        ];
        
        if (!empty($this->_boost)) {
            $_ret['fuzzy'][$_term]['boost'] = $this->_boost;
        }
        if (!empty($this->_fuzziness)) {
            $_ret['fuzzy'][$_term]['fuzziness'] = $this->_fuzziness;
        }
        if (!empty($this->_prefix_length)) {
            $_ret['fuzzy'][$_term]['prefix_length'] = $this->_prefix_length;
        }
        if (!empty($this->_max_expansions)) {
            $_ret['fuzzy'][$_term]['max_expansions'] = $this->_max_expansions;
        }
        
        return $_ret;
    }
}
