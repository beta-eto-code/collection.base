<?php

namespace Collection\Base;

use Collection\Base\Interfaces\CollectionInterface;
use Collection\Base\Interfaces\CollectionItemInterface;
use Collection\Base\Interfaces\CollectionStorageInterface;
use Collection\Base\Interfaces\GroupCollectionInterface;
use Collection\Base\Interfaces\ReadableCollectionInterface;
use Collection\Base\Storage\ArrayStorage;
use Collection\Base\Storage\IteratorStorage;
use EmptyIterator;
use Exception;
use Iterator;

class Collection implements CollectionInterface
{
    /**
     * @var CollectionItemInterface[]
     */
    protected array $items;
    private CollectionStorageInterface $storage;

    public function __construct(iterable $itemList = [], ?CollectionStorageInterface $storage = null)
    {
        $this->storage = $storage ?? new ArrayStorage();
        foreach ($itemList as $item) {
            $this->append($item);
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
        return new static($itemList, $storage);
    }

    public function findByKey(string $key, mixed $value): ?CollectionItemInterface
    {
        foreach ($this as $item) {
            if ($item->hasValueKey($key) && $item->assertValueByKey($key, $value)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @param callable $fnMap
     * @return array
     */
    public function map(callable $fnMap): array
    {
        return array_map($fnMap, iterator_to_array($this->storage));
    }

    public function getIterator(): Iterator
    {
        return $this->storage->getIterator();
    }

    /**
     * @param string $key
     * @return CollectionInterface
     */
    public function sortByAscending(string $key): ReadableCollectionInterface
    {
        $itemList = iterator_to_array($this->storage);
        usort($itemList, function (
            CollectionItemInterface $itemA,
            CollectionItemInterface $itemB
        ) use ($key): int {
            $valueA = $itemA->getValueByKey($key);
            $valueB = $itemB->getValueByKey($key);
            return $this->cmp($valueA, $valueB);
        });
        return $this->newCollection($itemList);
    }

    /**
     * @param string $key
     * @return CollectionInterface
     */
    public function sortByDescending(string $key): ReadableCollectionInterface
    {
        $itemList = iterator_to_array($this->storage);
        usort($itemList, function (
            CollectionItemInterface $itemA,
            CollectionItemInterface $itemB
        ) use ($key): int {
            $valueA = $itemA->getValueByKey($key);
            $valueB = $itemB->getValueByKey($key);
            return $this->cmp($valueA, $valueB) * -1;
        });
        return $this->newCollection($itemList);
    }

    public function cmp($valueA, $valueB): int
    {
        $valueA = $this->getNormalizedValueForCompare($valueA);
        $valueB = $this->getNormalizedValueForCompare($valueB);
        if (is_numeric($valueA) && is_numeric($valueB)) {
            return (int) ceil($valueA - $valueB);
        }

        $valueA = (string) $valueA;
        $valueB = (string) $valueB;
        return strcmp($valueA, $valueB);
    }

    private function getNormalizedValueForCompare(mixed $value): mixed
    {
        if (is_object($value) && method_exists($value, '__toString')) {
            return $value->__toString();
        }

        return $value;
    }

    /**
     * @param callable $fn - attribute CollectionItemInterface
     * @return CollectionItemInterface|null
     */
    public function find(callable $fn): ?CollectionItemInterface
    {
        foreach ($this as $item) {
            if ($fn($item) === true) {
                return $item;
            }
        }

        return null;
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
        foreach ($this as $item) {
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
        foreach ($this as $item) {
            $resultKey = $value = $item->hasValueKey($key) ? $item->getValueByKey($key) : null;
            if (!is_scalar($resultKey)) {
                $resultKey = md5(json_encode($value));
            }
            $result[$resultKey] = $isCallable ? $fnModifier($value) : $value;
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
        if (empty($value)) {
            return $this->newCollection([]);
        }

        return $this->newCollection([], new IteratorStorage($this->filterByKeyIterator($key, ...$value)));
    }

    private function filterByKeyIterator(string $key, mixed ...$value): Iterator
    {
        foreach ($this as $item) {
            if ($item->hasValueKey($key) && in_array($item->getValueByKey($key), $value)) {
                yield $item;
            }
        }
        return new EmptyIterator();
    }

    /**
     * @param callable $fn - attribute CollectionItemInterface
     * @return ReadableCollectionInterface
     */
    public function filter(callable $fn): ReadableCollectionInterface
    {
        return $this->newCollection([], new IteratorStorage($this->filterIterator($fn)));
    }

    private function filterIterator(callable $fn): Iterator
    {
        foreach ($this as $item) {
            if ($fn($item) === true) {
                yield $item;
            }
        }
        return new EmptyIterator();
    }

    public function append(CollectionItemInterface $item): void
    {
        $this->storage->append($item);
    }

    /**
     * @param string $key
     * @return GroupCollectionInterface[]|ReadableCollectionInterface
     */
    public function groupByKey(string $key): ReadableCollectionInterface
    {
        $list = [];
        foreach ($this->storage as $item) {
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
        foreach ($this->storage as $item) {
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
     * @return integer
     */
    public function count(): int
    {
        return count($this->storage);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $result = [];
        foreach ($this as $item) {
            $result[] = $item->jsonSerialize();
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public function first(): ?CollectionItemInterface
    {
        $current = $this->getIterator()->current();
        return $current instanceof CollectionItemInterface ? $current : null;
    }
}
