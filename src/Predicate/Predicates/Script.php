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

use JetBrains\PhpStorm\ArrayShape;

/**
 * Class Term
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Script extends AbstractPredicate {
    
    
    /**
     * @var array
     */
    protected array $_script;
    
    
    /**
     * Script constructor.
     * @param array $script
     */
    public function __construct(array $script) {
        $this->_script = $script;
    }
    
    
    /**
     * @param string $path
     * @return self
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function pathFix(string $path): self {
        return $this;
    }
    
    
    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    #[ArrayShape(['script' => "array"])]
    public function toArray(): array {
        return [
            'script' => [
                'script' => $this->_script,
            ],
        ];
    }
}
