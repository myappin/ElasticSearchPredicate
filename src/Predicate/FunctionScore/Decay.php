<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 17. 6. 2016
 * Time: 10:51
 */

namespace ElasticSearchPredicate\Predicate\FunctionScore;

use DusanKasan\Knapsack\Collection;
use ElasticSearchPredicate\Predicate\FunctionScore\Field\FieldInterface;
use ElasticSearchPredicate\Predicate\PredicateException;

/**
 * Class Decay
 * @package   ElasticSearchPredicate\Predicate\FunctionScore
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Decay extends AbstractFunction {


    /**
     * @var string
     */
    protected string $_type;


    /**
     * @var \DusanKasan\Knapsack\Collection
     */
    protected Collection $_fields;


    /**
     * Decay constructor.
     * @param string $type
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     */
    public function __construct(string $type) {
        $this->setType($type);
    }


    /**
     * @return string
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getType(): string {
        return $this->_type;
    }


    /**
     * @param string $type
     * @return $this
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setType(string $type): self {
        if (!in_array($type, [
            'linear',
            'exp',
            'gauss',
        ], true)
        ) {
            throw new PredicateException('Invalid decay function');
        }

        $this->_type = $type;

        return $this;
    }


    /**
     * @return \DusanKasan\Knapsack\Collection
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getFields(): Collection {
        return $this->_fields ?? ($this->_fields = new Collection([]));
    }


    /**
     * @param \ElasticSearchPredicate\Predicate\FunctionScore\Field\FieldInterface $field
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function addField(FieldInterface $field): self {
        $this->_fields = $this->getFields()->append($field);

        return $this;
    }


    /**
     * @return array
     * @throws \ElasticSearchPredicate\Predicate\PredicateException
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function toArray(): array {
        $_fields = $this->getFields();

        if ($_fields->isEmpty()) {
            throw new PredicateException('Decay should contain at least one field');
        }
        $_type = $this->_type;

        $_ret = [
            $_type => $this->getFields()->map(function(FieldInterface $item) {
                return $item->toArray();
            })->flatten(1)->toArray(),
        ];

        if (!empty($_query = $this->getQuery())) {
            $_ret['filter'] = $_query;
        }

        if (!empty($this->_weight)) {
            $_ret['weight'] = $this->_weight;
        }

        return $_ret;
    }


}
