<?php

namespace Collection\Base;

use ArrayAccess;
use ArrayIterator;
use Collection\Base\Interfaces\CollectionInterface;
use Collection\Base\Interfaces\CollectionItemInterface;
use Collection\Base\Interfaces\CollectionStorageInterface;
use Collection\Base\Interfaces\GroupCollectionInterface;
use Collection\Base\Interfaces\ReadableCollectionInterface;
use Exception;
use Iterator;

class MappedCollection implements CollectionInterface, ArrayAccess
{
    /**
     * @var CollectionItemInterface[]
     */
    protected array $list;
    private string $key;

    /**
     * @param CollectionItemInterface[] $itemList
     * @param string $key
     */
    public function __construct(iterable $itemList, string $key)
    {
        $this->list = [];
        $this->key = $key;

        foreach ($itemList as $item) {
            if ($item instanceof CollectionItemInterface && $item->hasValueKey($key)) {
                $this->append($item);
            }
        }
    }

    /**
     * @param CollectionItemInterface $itemList
     * @param CollectionStorageInterface|null $storage
     * @return ReadableCollectionInterface
     */
    public function newCollection(
        iterable $itemList = [],
        ?CollectionStorageInterface $storage = null
    ): ReadableCollectionInterface {
        return new static($itemList, $this->key);
    }

    public function findByKey(string $key, mixed $value): ?CollectionItemInterface
    {
        foreach ($this->list as $item) {
            if ($item->hasValueKey($key) && $item->assertValueByKey($key, $value)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @param callable $fn - attribute CollectionItemInterface
     * @return CollectionItemInterface|null
     */
    public function find(callable $fn): ?CollectionItemInterface
    {
        foreach ($this->list as $item) {
            if ($fn($item) === true) {
                return $item;
            }
        }

        return null;
    }

     /**
     * @param string $key
     * @return GroupCollectionInterface[]|ReadableCollectionInterface
     */
    public function groupByKey(string $key): ReadableCollectionInterface
    {
        $list = [];
        foreach ($this->list as $item) {
            $index = $item->getValueByKey($key);
            $list[$index][] = $item;
        }

        $collection = new Collection();
        foreach ($list as $index => $itemsList) {
            $group = new GroupCollection($key, $index, $itemsList);
            $collection->append($group);
        }

        return $collection;
    }

    /**
     * @param callable $fnCalcKeyValue - возвращает значение для группировки
     * @return GroupCollectionInterface[]|ReadableCollectionInterface
     */
    public function group(string $key, callable $fnCalcKeyValue): ReadableCollectionInterface
    {
        $list = [];
        foreach ($this->list as $item) {
            $index = $fnCalcKeyValue($item);
            $list[$index][] = $item;
        }

        $collection = new Collection();
        foreach ($list as $index => $itemsList) {
            $group = new GroupCollection($key, $index, $itemsList);
            $collection->append($group);
        }

        return $collection;
    }

    /**
     * @param string $key
     * @param string|null $indexKey
     * @param callable|null $fnModifier - attribute is mixed value by the key of the collection item
     * @return array
     */
    public function column(string $key, string $indexKey = null, callable $fnModifier = null): array
    {
        $result = [];
        $isCallable = $fnModifier !== null;
        foreach ($this->list as $item) {
            $itemKey = null;
            if (!empty($indexKey) && $item->hasValueKey($indexKey)) {
                $itemKey = $item->getValueByKey($indexKey);
            }

            $value = $item->hasValueKey($key) ? $item->getValueByKey($key) : null;
            if (empty($itemKey)) {
                $result[] = $isCallable ? $fnModifier($value) : $value;
            } else {
                $result[$itemKey] = $isCallable ? $fnModifier($value) : $value;
            }
        }

        return $result;
    }

    /**
     * @param string $key
     * @param callable|null $fnModifier - attribute is mixed value by the key of the collection item
     * @return array
     */
    public function unique(string $key, callable $fnModifier = null): array
    {
        $result = [];
        $isCallable = $fnModifier !== null;
        foreach ($this->list as $item) {
            $value = $item->hasValueKey($key) ? $item->getValueByKey($key) : null;
            $result[$value] = $isCallable ? $fnModifier($value) : $value;
        }

        return array_values($result);
    }

    /**
     * @param string $key
     * @param mixed ...$value
     * @return CollectionItemInterface[]|ReadableCollectionInterface
     */
    public function filterByKey(string $key, mixed ...$value): ReadableCollectionInterface
    {
        return $this->filter(function (CollectionItemInterface $item) use ($key, $value) {
            return $item->hasValueKey($key) && in_array($item->getValueByKey($key), $value);
        });
    }

    /**
     * @param callable $fn - attribute CollectionItemInterface
     * @return ReadableCollectionInterface
     */
    public function filter(callable $fn): ReadableCollectionInterface
    {
        return new static(array_filter($this->list, $fn), $this->key);
    }

    /**
     * @return CollectionItemInterface|null
     */
    public function first(): ?CollectionItemInterface
    {
        $first = current($this->list);
        return $first instanceof CollectionItemInterface ? $first : null;
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->list);
    }

    /**
     * @return integer
     */
    public function count(): int
    {
        return count($this->list);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $result = [];
        foreach ($this->list as $item) {
            $result[] = $item->jsonSerialize();
        }

        return $result;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->list[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return  $this->list[$offset] ?? null;
    }

    /**
     * @throws Exception
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof CollectionItemInterface)) {
            throw new Exception('Invalid data type for collection');
        }

        $this->list[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        if (isset($this->list[$offset])) {
            unset($this->list[$offset]);
        }
    }

    public function map(callable $fnMap): array
    {
        return array_map($fnMap, $this->list);
    }

    public function append(CollectionItemInterface $item): void
    {
        if ($item->hasValueKey($this->key)) {
            $indexKey = $item->getValueByKey($this->key);
            $this->list[$indexKey] = $item;
        }
    }

    public function remove(CollectionItemInterface $item): void
    {
        $indexForUnset = [];
        foreach ($this->list as $i => $listItem) {
            if ($listItem === $item) {
                $indexForUnset[] = $i;
            }
        }

        foreach ($indexForUnset as $i) {
            unset($this->list[$i]);
        }
        unset($indexForUnset);
    }
}
