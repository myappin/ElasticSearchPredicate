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
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class Missing
 * @package   ElasticSearchPredicate\Predicate\Predicates
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Missing extends AbstractPredicate implements BoostInterface {


    use BoostTrait;

    /**
     * @var string
     */
    protected string $_term;


    /**
     * Missing constructor.
     * @param string $term
     * @param array  $options
     */
    public function __construct(string $term, array $options = []) {
        $this->_term = $term;

        $this->configure($options);
    }


    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    #[ArrayShape(['bool' => "array"])]
    public function toArray(): array {
        $_term = $this->_term;

        $_ret = [
            'bool' => [
                'must_not' => [
                    'exists' => [
                        'field' => $_term,
                    ],
                ],
            ],
        ];

        if (!empty($this->_boost)) {
            $_ret['bool']['must_not']['exists']['boost'] = $this->_boost;
        }

        return $_ret;
    }
}
