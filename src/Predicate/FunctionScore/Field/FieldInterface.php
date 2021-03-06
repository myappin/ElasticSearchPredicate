<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.cz)
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 * @link      http://www.myappin.cz
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.cz)
 * Date: 17. 6. 2016
 * Time: 11:05
 */

namespace ElasticSearchPredicate\Predicate\FunctionScore\Field;

/**
 * Interface FieldInterface
 * @package   ElasticSearchPredicate\Predicate\FunctionScore\Field
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
interface FieldInterface {


    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function toArray(): array;


}
