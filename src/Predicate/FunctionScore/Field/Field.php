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
 * Class Field
 * @package   ElasticSearchPredicate\Predicate\FunctionScore\Field
 * @author    Martin Lonsky (martin@lonsky.net, +420 736 645876)
 */
class Field implements FieldInterface {


    /**
     * @var string
     */
    protected string $_name;


    /**
     * @var int|float|string
     */
    protected int|float|string $_origin;


    /**
     * @var int|float
     */
    protected int|float $_scale;


    /**
     * @var int|null
     */
    protected ?int $_offset;


    /**
     * @var int|float|null
     */
    protected int|float|null $_decay;


    /**
     * Field constructor.
     * @param string           $name
     * @param float|int|string $origin
     * @param int|float        $scale
     * @param int|null         $offset
     * @param float|int|null   $decay
     */
    public function __construct(string $name, float|int|string $origin, int|float $scale, ?int $offset = null, float|int|null $decay = null) {
        $this->setName($name);
        $this->setOrigin($origin);
        $this->setScale($scale);

        if ($offset !== null) {
            $this->setOffset($offset);
        }
        if ($decay !== null) {
            $this->setDecay($decay);
        }
    }


    /**
     * @return string
     */
    public function getName(): string {
        return $this->_name;
    }


    /**
     * @param string $name
     * @return Field
     */
    public function setName(string $name): self {
        $this->_name = $name;

        return $this;
    }


    /**
     * @return float|int|string
     */
    public function getOrigin(): float|int|string {
        return $this->_origin;
    }


    /**
     * @param float|int|string $origin
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setOrigin(float|int|string $origin): self {
        $this->_origin = $origin;

        return $this;
    }


    /**
     * @return float|int
     */
    public function getScale(): int|float {
        return $this->_scale;
    }


    /**
     * @param int|float $scale
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setScale(int|float $scale): self {
        $this->_scale = $scale;

        return $this;
    }


    /**
     * @return int|null
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
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
     * @return int|float|null
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function getDecay(): int|float|null {
        return $this->_decay;
    }


    /**
     * @param int|float $decay
     * @return $this
     * @author Martin Lonsky (martin.lonsky@myappin.cz, +420 736 645 876)
     */
    public function setDecay(int|float $decay): self {
        $this->_decay = $decay;

        return $this;
    }


    /**
     * @return array
     * @author Martin Lonsky (martin@lonsky.net, +420 736 645876)
     */
    public function toArray(): array {
        $_name = $this->_name;

        $_ret = [
            $_name => [
                'origin' => $this->_origin,
                'scale'  => $this->_scale,
            ],
        ];

        if (isset($this->_offset)) {
            $_ret[$_name]['offset'] = $this->_offset;
        }
        if (isset($this->_decay)) {
            $_ret[$_name]['decay'] = $this->_decay;
        }

        return $_ret;
    }


}
