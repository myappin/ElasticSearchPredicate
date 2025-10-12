<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 9:55
 */

namespace ElasticSearchPredicate\Predicate;

use DusanKasan\Knapsack\Collection;
use ElasticSearchPredicate\Predicate\Predicates\PredicateInterface;
use ElasticSearchPredicate\Predicate\PredicateSet\InnerHitsInterface;
use ElasticSearchPredicate\Predicate\PredicateSet\PredicateSetTrait;

/**
 * Class PredicateSet
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @method PredicateSet Fuzzy(string $term, bool|float|int|string $value, array $options = [])
 * @method PredicateSet Term(string $term, bool|float|int|string $value, array $options = [])
 * @method PredicateSet Terms(string $term, array $values, array $options = [])
 * @method PredicateSet Match(string $match, bool|float|int|string $query, array $options = [])
 * @method PredicateSet MatchAll()
 * @method PredicateSet Range(string $term, int|float|null $from, int|float|null $to = null, array $options = [])
 * @method PredicateSet QueryString(bool|float|int|string $query, array $fields = [], array $options = [])
 * @method PredicateSet MultiMatch(bool|float|int|string $query, array $fields, array $options = [])
 * @method PredicateSet Exists(string $term, array $options = [])
 * @method PredicateSet Missing(string $term, array $options = [])
 * @method PredicateSet Script(array $script)
 * @property PredicateSet AND
 * @property PredicateSet and
 * @property PredicateSet OR
 * @property PredicateSet or
 */
class PredicateSet implements PredicateSetInterface {
    
    
    use PredicateSetTrait;
    
    public const C_AND = 'AND';
    
    
    public const C_OR = 'OR';
    public const HOOK_BEFORE_TO_ARRAY = 'beforeToArray';
    /**
     * @var string
     */
    protected string $_path = '';
    /**
     * @var PredicateSet|null
     */
    protected ?PredicateSet $_unnest = null;
    /**
     * @var string
     */
    protected string $_combiner = self::C_AND;
    /**
     * @var Collection
     */
    protected Collection $_predicates;
    /**
     * @var PredicateInterface|null
     */
    protected ?PredicateInterface $_last = null;
    protected array $_hooks = [];
    
    /**
     * PredicateSet constructor.
     * @param PredicateSet|null $unnest
     */
    public function __construct(PredicateSet $unnest = null) {
        $this->_unnest = $unnest;
        $this->_predicates = new Collection([]);
    }
    
    /**
     * @param string $path
     * @param string $_value
     * @return string
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public static function pathFixer(string $path, string $_value): string {
        if (empty($path)) {
            return $_value;
        }
        
        $_parts = explode('.', $path);
        $_found = false;
        for ($i = count($_parts); $i >= 1; $i--) {
            $_candidate = implode('.', array_slice($_parts, -$i));
            if (str_starts_with($_value, $_candidate)) {
                $_missing = implode('.', array_slice($_parts, 0, count($_parts) - $i));
                if ($_missing !== '') {
                    $_value = $_missing . '.' . $_value;
                }
                $_found = true;
                break;
            }
        }
        if (!$_found) {
            $_value = $path . '.' . $_value;
        }
        
        return $_value;
    }
    
    /**
     * @param $name
     * @param $arguments
     * @return $this
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function __call($name, $arguments): self {
        $name = preg_replace('/[^a-z0-9_]+/i', '', $name);
        
        if ($name === 'Match') {
            $name = 'MatchSome';
        }
        
        $_class = 'ElasticSearchPredicate\Predicate\Predicates\\' . $name;
        
        if (!class_exists($_class)) {
            throw new PredicateException(sprintf('Predicate %s does not exist', $name));
        }
        $this->_last?->setCombiner($this->_combiner);
        
        /** @var PredicateInterface $_predicate */
        if (empty($arguments)) {
            $_predicate = new $_class();
        } else {
            $_predicate = new $_class(...$arguments);
        }
        
        $this->_last = $_predicate;
        $this->_predicates = $this->_predicates->append($_predicate);
        
        $this->_combiner = self::C_AND;
        
        return $this;
    }
    
    /**
     * @param $name
     * @return $this|null
     * @throws PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function __get($name) {
        if (in_array($_combiner = strtoupper($name), [
            self::C_AND,
            self::C_OR,
        ], true)
        ) {
            $this->setCombiner($_combiner);
            
            return $this;
        }
        
        throw new PredicateException(sprintf('Property %s does not exist', $name));
    }
    
    /**
     * @param string   $hook
     * @param callable $function
     * @return self
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function addHook(string $hook, callable $function): self {
        if (!isset($this->_hooks[$hook])) {
            $this->_hooks[$hook] = [];
        }
        
        $this->_hooks[$hook][] = $function;
        
        return $this;
    }
    
    /**
     * @param PredicateInterface $predicate
     * @return PredicateSet
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function and(PredicateInterface $predicate): PredicateSet {
        return $this->andPredicate($predicate);
    }
    
    /**
     * @param PredicateInterface $predicate
     * @return PredicateSet
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function andPredicate(PredicateInterface $predicate): PredicateSet {
        $this->setCombiner(self::C_AND);
        
        return $this->append($predicate);
    }
    
    /**
     * @param PredicateInterface $predicate
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function append(PredicateInterface $predicate): self {
        $this->_last?->setCombiner($this->_combiner);
        
        if ($predicate instanceof self) {
            if (!$predicate->isEmpty()) {
                $this->_predicates = $this->_predicates->append($predicate);
                $this->_last = $predicate;
            }
        } else {
            $this->_predicates = $this->_predicates->append($predicate);
            $this->_last = $predicate;
        }
        
        $this->_combiner = self::C_AND;
        
        return $this;
    }
    
    /**
     * @param string $type
     * @return HasChildPredicateSet
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    public function child(string $type): HasChildPredicateSet {
        $this->_last?->setCombiner($this->_combiner);
        
        $_nest = new HasChildPredicateSet($this);
        $_nest->setType($type);
        $this->_last = $_nest;
        
        $this->_predicates = $this->_predicates->append($_nest);
        
        $this->_combiner = self::C_AND;
        
        return $_nest;
    }
    
    /**
     * @return FilterPredicateSet
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    public function filter(): FilterPredicateSet {
        $this->_last?->setCombiner($this->_combiner);
        
        $_nest = new FilterPredicateSet($this);
        $_nest->setPath($this->_path);
        
        $this->_last = $_nest;
        
        $this->_predicates = $this->_predicates->append($_nest);
        
        $this->_combiner = self::C_AND;
        
        return $_nest;
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
        $combiner = strtoupper($combiner);
        if ($combiner !== self::C_AND && $combiner !== self::C_OR) {
            throw new PredicateException('Unsupported combiner');
        }
        $this->_combiner = $combiner;
        
        return $this;
    }
    
    /**
     * @return string
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getPath(): string {
        return $this->_path;
    }
    
    /**
     * @param string $path
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setPath(string $path): self {
        $this->_path = $path;
        
        return $this;
    }
    
    /**
     * @return Collection
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getPredicates(): Collection {
        return $this->_predicates;
    }
    
    /**
     * @param Collection $predicates
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setPredicates(Collection $predicates): self {
        $this->_predicates = $predicates;
        
        return $this;
    }
    
    /**
     * @return bool
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    public function isEmpty(): bool {
        return $this->_predicates->isEmpty();
    }
    
    /**
     * @return PredicateSet
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    public function nest(): PredicateSet {
        $this->_last?->setCombiner($this->_combiner);
        
        $_nest = new PredicateSet($this);
        $_nest->setPath($this->_path);
        
        $this->_last = $_nest;
        
        $this->_predicates = $this->_predicates->append($_nest);
        
        $this->_combiner = self::C_AND;
        
        return $_nest;
    }
    
    /**
     * @param string $path
     * @return NestedPredicateSet
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    public function nested(string $path): NestedPredicateSet {
        $this->_last?->setCombiner($this->_combiner);
        
        $_nest = new NestedPredicateSet($this);
        $_nest->setPath($path);
        
        $this->_last = $_nest;
        
        $this->_predicates = $this->_predicates->append($_nest);
        
        $this->_combiner = self::C_AND;
        
        return $_nest;
    }
    
    /**
     * @return Collection
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    public function normalize(): Collection {
        return $this->_predicates->filter(function ($predicate) {
            if ($predicate instanceof PredicateSet) {
                if ($predicate instanceof InnerHitsInterface && $predicate->hasInnerHits()) {
                    return true;
                }
                
                return $predicate->isEmpty() === false;
            }
            
            return true;
        });
    }
    
    /**
     * @return PredicateSet
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function not(): PredicateSet {
        $this->_last?->setCombiner($this->_combiner);
        
        $_not = new NotPredicateSet($this);
        $_not->setPath($this->_path);
        
        $this->_last = $_not;
        $this->_predicates = $this->_predicates->append($_not);
        
        $this->_combiner = self::C_AND;
        
        return $_not;
    }
    
    /**
     * @param PredicateInterface $predicate
     * @return PredicateSet
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function or(PredicateInterface $predicate): PredicateSet {
        return $this->andPredicate($predicate);
    }
    
    /**
     * @param PredicateInterface $predicate
     * @return PredicateSet
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function orPredicate(PredicateInterface $predicate): PredicateSet {
        $this->setCombiner(self::C_OR);
        
        return $this->append($predicate);
    }
    
    /**
     * @param string $type
     * @return HasParentPredicateSet
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    public function parent(string $type): HasParentPredicateSet {
        $this->_last?->setCombiner($this->_combiner);
        
        $_nest = new HasParentPredicateSet($this);
        $_nest->setType($type);
        
        $this->_last = $_nest;
        
        $this->_predicates = $this->_predicates->append($_nest);
        
        $this->_combiner = self::C_AND;
        
        return $_nest;
    }
    
    /**
     * @param string $path
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function pathFix(string $path): self {
        if (!empty($path)) {
            $this->_path = self::pathFixer($path, $this->_path);
        }
        
        foreach ($this->_predicates as $predicate) {
            $predicate->pathFix($this->_path);
        }
        
        return $this;
    }
    
    /**
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function toArray(): array {
        $_predicate_set = $this;
        
        if (isset($this->_hooks[self::HOOK_BEFORE_TO_ARRAY])) {
            $_hooks = $this->_hooks[self::HOOK_BEFORE_TO_ARRAY];
            $this->_hooks[self::HOOK_BEFORE_TO_ARRAY] = [];
            
            foreach ($_hooks as $function) {
                /** @var PredicateSet $_predicate_set */
                $_predicate_set = $function($_predicate_set);
                $_predicate_set->setPath($this->getPath());
            }
        }
        
        $_predicates = $_predicate_set->normalize();
        
        $_size = $_predicates->size();
        
        if ($_size < 1) {
            return [];
        }
        if ($_size === 1) {
            return $_predicates->first()->pathFix($_predicate_set->_path)->toArray();
        }
        
        if ($_size === 2) {
            if ($_predicates->first()->getCombiner() === self::C_AND) {
                return [
                    'bool' => [
                        'must' => $_predicates->map(static function (PredicateInterface $predicate) use ($_predicate_set) {
                            return $predicate->pathFix($_predicate_set->_path)->toArray();
                        })->values()->toArray(),
                    ],
                ];
            }
            
            return [
                'bool' => [
                    'should' => $_predicates->map(static function (PredicateInterface $predicate) use ($_predicate_set) {
                        return $predicate->pathFix($_predicate_set->_path)->toArray();
                    })->values()->toArray(),
                ],
            ];
        }
        
        $_combiner = null;
        $_index = 0;
        $_partitions = $_predicates->partitionBy(function (PredicateInterface $predicate) use (&$_combiner, &$_index) {
            if ($_combiner === PredicateSet::C_OR) {
                $_index++;
            }
            $_combiner = $predicate->getCombiner();
            
            return $_index;
        });
        
        if ($_partitions->sizeIs(1)) {
            return [
                'bool' => $_partitions->map(static function (Collection $partition) use ($_predicate_set) {
                    if ($partition->first()->getCombiner() === PredicateSet::C_AND) {
                        return [
                            'must' => $partition->map(static function (PredicateInterface $predicate) use ($_predicate_set) {
                                return $predicate->pathFix($_predicate_set->_path)->toArray();
                            })->values()->toArray(),
                        ];
                    }
                    
                    return [
                        'should' => $partition->map(static function (PredicateInterface $predicate) use ($_predicate_set) {
                            return $predicate->pathFix($_predicate_set->_path)->toArray();
                        })->values()->toArray(),
                    ];
                })->first(),
            ];
        }
        
        return [
            'bool' => [
                'should' => $_partitions->map(static function (Collection $partition) use ($_predicate_set) {
                    if ($partition->sizeIs(1)) {
                        return $partition->first()->pathFix($_predicate_set->_path)->toArray();
                    }
                    
                    if ($partition->first()->getCombiner() === PredicateSet::C_AND) {
                        return [
                            'bool' => [
                                'must' => $partition->map(static function (PredicateInterface $predicate) use ($_predicate_set) {
                                    return $predicate->pathFix($_predicate_set->_path)->toArray();
                                })->values()->toArray(),
                            ],
                        ];
                    }
                    
                    return [
                        'bool' => [
                            'should' => $partition->map(static function (PredicateInterface $predicate) use ($_predicate_set) {
                                return $predicate->pathFix($_predicate_set->_path)->toArray();
                            })->values()->toArray(),
                        ],
                    ];
                })->values()->toArray(),
            ],
        ];
    }
    
    
    /**
     * @return PredicateSet
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    public function unnest(): PredicateSet {
        if (empty($this->_unnest)) {
            throw new PredicateException('Can not unnest not nested predicate');
        }
        
        $_unnest = $this->_unnest;
        $this->_unnest = null;
        
        return $_unnest;
    }
    
    
}
