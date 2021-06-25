<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 24. 5. 2016
 * Time: 13:19
 */

namespace ElasticSearchPredicate\Endpoint\Fields;

use ElasticSearchPredicate\Endpoint\EndpointException;

/**
 * Class FieldsTrait
 * @package   ElasticSearchPredicate\Endpoint\Fields
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
trait FieldsTrait {


    /**
     * @var array
     */
    protected array $_fields = [];


    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function getFields(): array {
        return $this->_fields;
    }


    /**
     * @param array $fields
     * @return $this
     * @throws \ElasticSearchPredicate\Endpoint\EndpointException
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function fields(array $fields): self {
        foreach ($fields as $field) {
            if (!is_string($field)) {
                throw new EndpointException('Fields should by array of string');
            }
        }
        $this->_fields = $fields;

        return $this;
    }


}
