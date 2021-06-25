<?php
declare(strict_types=1);
/**
 * MyAppIn (http://www.myappin.com)
 * @author    Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
 * @link      http://www.myappin.com
 * @copyright Copyright (c) MyAppIn s.r.o. (http://www.myappin.com)
 */

namespace ElasticSearchPredicate\Predicate\PredicateSet;

use JetBrains\PhpStorm\Pure;
use stdClass;

/**
 * Class InnerHits
 * @package   ElasticSearchPredicate\Predicate\PredicateSet
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class InnerHits {


    /**
     * @var string|null
     */
    protected ?string $_name = null;


    /**
     * @var int|null
     */
    protected ?int $_size = null;


    /**
     * @var int|null
     */
    protected ?int $_offset = null;


    /**
     * InnerHits constructor.
     * @param string|null $name
     */
    public function __construct(?string $name) {
        if ($name) {
            $this->setName($name);
        }
    }


    /**
     * @return null|string
     */
    public function getName(): ?string {
        return $this->_name;
    }


    /**
     * @param string $name
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setName(string $name): self {
        $this->_name = $name;

        return $this;
    }


    /**
     * @return int|null
     */
    public function getSize(): ?int {
        return $this->_size;
    }


    /**
     * @param int $size
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setSize(int $size): self {
        $this->_size = $size;

        return $this;
    }


    /**
     * @return int|null
     */
    public function getOffset(): ?int {
        return $this->_offset;
    }


    /**
     * @param int $offset
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setOffset(int $offset): self {
        $this->_offset = $offset;

        return $this;
    }


    /**
     * @return array|\stdClass
     * @author Martin Lonsky (martin.lonsky@myappin.com, +420736645876)
     */
    #[Pure]
    public function toArray(): array|stdClass {
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

        return empty($_ret) ? new stdClass() : $_ret;
    }


}
