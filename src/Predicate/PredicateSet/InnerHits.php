<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.com)
 * @author    Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
 * @link      http://www.myappin.com
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.com)
 */

namespace ElasticSearchPredicate\Predicate\PredicateSet;


/**
 * Class InnerHits
 * @package   ElasticSearchPredicate\Predicate\PredicateSet
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class InnerHits {


    /**
     * @var string|null
     */
    protected $_name = null;


    /**
     * @var int|null
     */
    protected $_size = null;


    /**
     * @var int|null
     */
    protected $_offset = null;


    /**
     * InnerHits constructor.
     * @param string|null $name
     */
    public function __construct(string $name = null) {
        if ($name) {
            $this->setName($name);
        }
    }


    /**
     * @return null|string
     */
    public function getName() {
        return $this->_name;
    }


    /**
     * @param null|string $name
     * @return InnerHits
     */
    public function setName(string $name) {
        $this->_name = $name;

        return $this;
    }


    /**
     * @return int|null
     */
    public function getSize() {
        return $this->_size;
    }


    /**
     * @param int|null $size
     * @return InnerHits
     */
    public function setSize(int $size) {
        $this->_size = $size;

        return $this;
    }


    /**
     * @return int|null
     */
    public function getOffset() {
        return $this->_offset;
    }


    /**
     * @param int|null $offset
     * @return InnerHits
     */
    public function setOffset(int $offset) {
        $this->_offset = $offset;

        return $this;
    }


    /**
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     * @return array|\stdClass
     */
    public function toArray() {
        $_ret = [];

        if ($this->_name) {
            $_ret['name'] = $this->_name;
        }
        if ($this->_size) {
            $_ret['size'] = $this->_size;
        }
        if ($this->_offset) {
            $_ret['offset'] = $this->_offset;
        }

        return empty($_ret) ? new \stdClass() : $_ret;
    }


}