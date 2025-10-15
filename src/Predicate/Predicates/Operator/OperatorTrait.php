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
 * Date: 25. 5. 2016
 * Time: 22:36
 */

namespace ElasticSearchPredicate\Predicate\Predicates\Operator;

use ElasticSearchPredicate\Predicate\PredicateException;
use ElasticSearchPredicate\Predicate\Predicates\MatchSome;
use ElasticSearchPredicate\Predicate\Predicates\MultiMatch;

/**
 * Class OperatorTrait
 * @package   ElasticSearchPredicate\Predicate\Predicates\Operator
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait OperatorTrait {
    
    
    /**
     * @var string
     */
    protected string $_operator;
    
    
    /**
     * @var array
     */
    protected array $_operators = [
        'and',
        'or',
    ];
    
    
    /**
     * @param string $operator
     * @return OperatorTrait|MatchSome|MultiMatch
     * @throws PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function operator(string $operator): self {
        if (!in_array($operator, $this->_operators, true)) {
            throw new PredicateException('Operator is not valid');
        }
        
        $this->_operator = $operator;
        $this->_simple = false;
        
        return $this;
    }
    
    
}
