<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 25. 5. 2016
 * Time: 22:36
 */

namespace ElasticSearchPredicate\Predicate\Predicates\Analyzer;

use ElasticSearchPredicate\Predicate\Predicates\QueryString;

/**
 * Class AnalyzerTrait
 * @package   ElasticSearchPredicate\Predicate\Predicates\Analyzer
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait AnalyzerTrait {
    
    
    /**
     * @var string
     */
    protected string $_analyzer;
    
    
    /**
     * @param string $analyzer
     * @return AnalyzerTrait|QueryString
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function analyzer(string $analyzer): self {
        $this->_analyzer = $analyzer;
        
        return $this;
    }
    
    
}
