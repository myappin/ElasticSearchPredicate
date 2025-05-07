<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 27. 5. 2016
 * Time: 16:51
 */

namespace ElasticSearchPredicate\Predicate;

use DusanKasan\Knapsack\Collection;
use ElasticSearchPredicate\Predicate\FunctionScore\FunctionInterface;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class FunctionScore
 * nested mappings
 * @package   ElasticSearchPredicate\Predicate
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class FunctionScore extends PredicateSet {


    /**
     * @var int|float|null
     */
    protected int|float|null $_max_boost;


    /**
     * @var string|null
     */
    protected ?string $_score_mode = null;


    /**
     * @var string|null
     */
    protected ?string $_boost_mode = null;


    /**
     * @var float|int|null
     */
    protected float|int|null $_min_score;


    /**
     * @var Collection
     */
    protected Collection $_functions;


    /**
     * @return string|null
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getBoostMode(): ?string {
        return $this->_boost_mode;
    }


    /**
     * @param string|null $boost_mode
     * @return $this
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setBoostMode(?string $boost_mode): self {
        if (!in_array($boost_mode, [
            'multiply',
            'replace',
            'sum',
            'avg',
            'max',
            'min',
            null,
        ], true)
        ) {
            throw new PredicateException('Invalid boost type');
        }

        $this->_boost_mode = $boost_mode;

        return $this;
    }


    /**
     * @return \DusanKasan\Knapsack\Collection
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getFunctions(): Collection {
        return $this->_functions ?? ($this->_functions = new Collection([]));
    }


    /**
     * @return float|int|null
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getMaxBoost(): float|int|null {
        return $this->_max_boost;
    }


    /**
     * @param float|int|null $max_boost
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setMaxBoost(float|int|null $max_boost): self {
        $this->_max_boost = $max_boost;

        return $this;
    }


    /**
     * @return float|int|null
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getMinScore(): float|int|null {
        return $this->_min_score;
    }


    /**
     * @param float|int|null $min_score
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setMinScore(float|int|null $min_score): self {
        $this->_min_score = $min_score;

        return $this;
    }


    /**
     * @return string|null
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getScoreMode(): ?string {
        return $this->_score_mode;
    }


    /**
     * @param string|null $score_mode
     * @return $this
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setScoreMode(?string $score_mode): self {
        if (!in_array($score_mode, [
            'multiply',
            'sum',
            'avg',
            'first',
            'max',
            'min',
            null,
        ], true)
        ) {
            throw new PredicateException('Invalid score mode');
        }

        $this->_score_mode = $score_mode;

        return $this;
    }


    /**
     * @param \ElasticSearchPredicate\Predicate\FunctionScore\FunctionInterface $function
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function addFunction(FunctionInterface $function): self {
        $this->_functions = $this->getFunctions()->append($function);

        return $this;
    }


    /**
     * @return array
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    #[ArrayShape(['function_score' => "array"])]
    public function toArray(): array {
        $_functions = $this->getFunctions();
        if ($_functions->isEmpty()) {
            throw new PredicateException('FunctionScore should contain at least one function');
        }

        $_ret = [
            'function_score' => [
            ],
        ];

        if (!empty($_query = parent::toArray())) {
            $_ret['function_score']['query'] = $_query;
        }

        if (isset($this->_boost_mode)) {
            $_ret['function_score']['boost_mode'] = $this->_boost_mode;
        }
        if (isset($this->_max_boost)) {
            $_ret['function_score']['max_boost'] = $this->_max_boost;
        }
        if (isset($this->_score_mode)) {
            $_ret['function_score']['score_mode'] = $this->_score_mode;
        }
        if (isset($this->_min_score)) {
            $_ret['function_score']['min_score'] = $this->_min_score;
        }

        $_ret['function_score']['functions'] = $_functions->map(function(FunctionInterface $item) {
            return $item->toArray();
        })->toArray();

        return $_ret;
    }


}
