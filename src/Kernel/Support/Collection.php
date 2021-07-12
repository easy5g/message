<?php
/**
 * User: zhouhua
 * Date: 2021/7/9
 * Time: 10:32 上午
 */

namespace Easy5G\Kernel\Support;


use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Serializable;
use Traversable;

class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable, Serializable
{
    protected $items = [];

    /**
     * Collection constructor.
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * all
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * only
     * @param array $keys
     * @return $this
     */
    public function only(array $keys)
    {
        $return = [];

        foreach ($keys as $key) {
            $value = $this->get($key);

            if (!is_null($value)) {
                $return[$key] = $value;
            }
        }

        return new static($return);
    }

    /**
     * except
     * @param $keys
     * @return $this
     */
    public function except($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return new static(Arr::except($this->items, $keys));
    }

    /**
     * merge
     * @param $items
     * @return $this
     */
    public function merge($items)
    {
        $clone = clone $this;

        foreach ($items as $key => $value) {
            $clone->set($key, $value);
        }

        return $clone;
    }

    /**
     * has
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return Arr::has($this->items, $key);
    }

    /**
     * first
     * @return mixed
     */
    public function first()
    {
        $clone = $this->items;

        return reset($clone);
    }

    /**
     * last
     * @return mixed
     */
    public function last()
    {
        $clone = $this->items;

        return end($clone);
    }

    /**
     * add
     * @param $key
     * @param $value
     */
    public function add($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * set
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        Arr::set($this->items, $key, $value);
    }

    /**
     * get
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }

    /**
     * forget
     * @param $key
     */
    public function forget($key)
    {
        Arr::forget($this->items, $key);
    }

    /**
     * toArray
     * @return array
     */
    public function toArray()
    {
        return $this->all();
    }

    /**
     * toJson
     * @param int $option
     * @return false|string
     */
    public function toJson($option = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this->all(), $option);
    }

    /**
     * __toString
     * @return false|string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * jsonSerialize
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->items;
    }

    /**
     * serialize
     * @return string
     */
    public function serialize()
    {
        return serialize($this->items);
    }

    /**
     * getIterator
     * @return ArrayIterator|Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * count
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * unserialize
     * @param string $serialized
     * @return mixed|void
     */
    public function unserialize($serialized)
    {
        return $this->items = unserialize($serialized);
    }

    /**
     * __get
     * @param $key
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * __set
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * __isset
     * @param $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * __unset
     * @param $key
     */
    public function __unset($key)
    {
        $this->forget($key);
    }

    /**
     * offsetExists
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * offsetUnset
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            $this->forget($offset);
        }
    }

    /**
     * offsetGet
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * offsetSet
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }
}