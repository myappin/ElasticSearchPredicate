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

use ElasticSearchPredicate\Endpoint\Filter\FilterTrait;
use ElasticSearchPredicate\Predicate\PredicateException;
use ElasticSearchPredicate\Predicate\PredicateSet;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class Neural
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Neural extends AbstractPredicate {
    
    use FilterTrait;
    
    /**
     * @var string
     */
    protected string $_query_text;
    
    /**
     * @var string
     */
    protected string $_field;
    
    /**
     * @var string|null
     */
    protected ?string $_query_image = null;
    
    /**
     * @var array|null map token => weight
     */
    protected ?array $_query_tokens = null;
    
    /**
     * @var int|null
     */
    protected ?int $_k = null;
    
    /**
     * @var float|null
     */
    protected ?float $_min_score = null;
    
    /**
     * @var float|null
     */
    protected ?float $_max_distance = null;
    
    /**
     * @var string|null
     */
    protected ?string $_model_id = null;
    
    /**
     * @var string|null
     */
    protected ?string $_inference_id = null;
    
    /**
     * @var array|null
     */
    protected ?array $_method_parameters = null;
    
    /**
     * @var bool|array|null
     */
    protected bool|array|null $_rescore = null;
    
    /**
     * @var bool|null
     */
    protected ?bool $_expand_nested_docs = null;
    
    /**
     * @var string|null
     */
    protected ?string $_semantic_field_search_analyzer = null;
    
    /**
     * Neural constructor.
     * @param string $query_text
     * @param string $vector_field
     * @param array  $options
     * @throws PredicateException
     */
    public function __construct(string $query_text, string $vector_field, array $options = []) {
        $this->_query_text = $query_text;
        $this->_field = $vector_field;
        
        $this->_other_options = [
            'k',
            'min_score',
            'max_distance',
            'model_id',
            'inference_id',
            'method_parameters',
            'rescore',
            'expand_nested_docs',
            'semantic_field_search_analyzer',
            'query_image',
            'query_tokens',
        ];
        $this->configure($options);
        
        // basic validation on tokens
        if ($this->_query_tokens !== null) {
            foreach ($this->_query_tokens as $token => $weight) {
                if (!is_string($token) || (!is_float($weight) && !is_int($weight))) {
                    throw new PredicateException('query_tokens must be a map of token(string) to weight(float)');
                }
            }
        }
        
        // enforce exclusivity among k / min_score / max_distance
        $_count_vars = 0;
        foreach ([
                     $this->_k,
                     $this->_min_score,
                     $this->_max_distance,
                 ] as $v) {
            if ($v !== null) {
                $_count_vars++;
            }
        }
        if ($_count_vars > 1) {
            throw new PredicateException('Only one of k, min_score, or max_distance can be specified');
        }
        
        // enforce exclusivity between model_id and semantic_field_search_analyzer
        if (!empty($this->_model_id) && !empty($this->_semantic_field_search_analyzer)) {
            throw new PredicateException('model_id cannot be used together with semantic_field_search_analyzer');
        }
        
        // require at least one of query_text, query_image, query_tokens
        if ($this->_query_text === '' && $this->_query_image === null && $this->_query_tokens === null) {
            throw new PredicateException('At least one of query_text, query_image, or query_tokens must be provided');
        }
    }
    
    /**
     * Optional expand nested docs
     * @param bool $expand
     * @return $this
     */
    public function expand_nested_docs(bool $expand): self {
        $this->_expand_nested_docs = $expand;
        
        return $this;
    }
    
    
    /**
     * @return string
     */
    public function getField(): string {
        return $this->_field;
    }
    
    /**
     * @param string $field
     * @return $this
     */
    public function setField(string $field): self {
        $this->_field = $field;
        
        return $this;
    }
    
    /**
     * Set inference id (precomputed embedding reference)
     * @param string $inference_id
     * @return $this
     */
    public function inference_id(string $inference_id): self {
        $inference_id = trim($inference_id);
        if ($inference_id !== '') {
            $this->_inference_id = $inference_id;
        }
        
        return $this;
    }
    
    /**
     * Set k (number of nearest neighbors)
     * @param int $k
     * @return $this
     * @throws PredicateException
     */
    public function k(int $k): self {
        if ($k <= 0) {
            throw new PredicateException('k must be greater than 0');
        }
        $this->_k = $k;
        $this->_min_score = null;
        $this->_max_distance = null;
        
        return $this;
    }
    
    /**
     * Set maximum distance threshold
     * @param float $maxDistance
     * @return $this
     */
    public function max_distance(float $maxDistance): self {
        $this->_max_distance = $maxDistance;
        $this->_k = null;
        $this->_min_score = null;
        
        return $this;
    }
    
    /**
     * Optional method parameters
     * @param array $params
     * @return $this
     */
    public function method_parameters(array $params): self {
        $this->_method_parameters = $params;
        
        return $this;
    }
    
    /**
     * Set minimum score threshold
     * @param float $minScore
     * @return $this
     */
    public function min_score(float $minScore): self {
        $this->_min_score = $minScore;
        $this->_k = null;
        $this->_max_distance = null;
        
        return $this;
    }
    
    /**
     * Set model id used for inference
     * @param string $model_id
     * @return $this
     */
    public function model_id(string $model_id): self {
        $model_id = trim($model_id);
        if ($model_id !== '') {
            $this->_model_id = $model_id;
        }
        
        return $this;
    }
    
    /**
     * @param string $path
     * @return self
     */
    public function pathFix(string $path): self {
        if (!empty($path)) {
            $this->_field = PredicateSet::pathFixer($path, $this->_field);
        }
        
        return $this;
    }
    
    /**
     * Optional base64 encoded query image
     * @param string $imageBase64
     * @return $this
     */
    public function query_image(string $imageBase64): self {
        $imageBase64 = trim($imageBase64);
        if ($imageBase64 !== '') {
            $this->_query_image = $imageBase64;
        }
        
        return $this;
    }
    
    /**
     * Optional sparse vector tokens
     * @param array $tokens map token => weight
     * @return $this
     */
    public function query_tokens(array $tokens): self {
        $this->_query_tokens = $tokens;
        return $this;
    }
    
    /**
     * Optional rescore configuration or enable
     * @param bool|array $rescore
     * @return $this
     */
    public function rescore(bool|array $rescore): self {
        $this->_rescore = $rescore;
        
        return $this;
    }
    
    /**
     * Optional semantic field search analyzer (mutually exclusive with model_id)
     * @param string $analyzer
     * @return $this
     */
    public function semantic_field_search_analyzer(string $analyzer): self {
        $analyzer = trim($analyzer);
        if ($analyzer !== '') {
            $this->_semantic_field_search_analyzer = $analyzer;
        }
        
        return $this;
    }
    
    /**
     * @return array
     */
    #[ArrayShape(['neural' => 'array'])]
    public function toArray(): array {
        $_ret = [];
        
        if ($this->_query_text !== '') {
            $_ret['query_text'] = $this->_query_text;
        }
        if (!empty($this->_query_image)) {
            $_ret['query_image'] = $this->_query_image;
        }
        if (!empty($this->_query_tokens)) {
            $_ret['query_tokens'] = $this->_query_tokens;
        }
        
        if (!empty($this->_k)) {
            $_ret['k'] = $this->_k;
        } elseif (!empty($this->_min_score)) {
            $_ret['min_score'] = $this->_min_score;
        } elseif (!empty($this->_max_distance)) {
            $_ret['max_distance'] = $this->_max_distance;
        }
        
        if (!empty($this->_model_id)) {
            $_ret['model_id'] = $this->_model_id;
        }
        if (!empty($this->_inference_id)) {
            $_ret['inference_id'] = $this->_inference_id;
        }
        if (!empty($this->_semantic_field_search_analyzer)) {
            $_ret['semantic_field_search_analyzer'] = $this->_semantic_field_search_analyzer;
        }
        
        if (!empty($_filter = $this->getFilter())) {
            $_ret['filter'] = $_filter;
        }
        
        if (!empty($this->_method_parameters)) {
            $_ret['method_parameters'] = $this->_method_parameters;
        }
        if ($this->_rescore !== null) {
            $_ret['rescore'] = $this->_rescore;
        }
        if ($this->_expand_nested_docs !== null) {
            $_ret['expand_nested_docs'] = $this->_expand_nested_docs;
        }
        
        return [
            'neural' => [
                $this->_field => $_ret,
            ],
        ];
    }
    
}
