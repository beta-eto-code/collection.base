<?php

namespace Collection\Base\Interfaces;

use IteratorAggregate;
use Countable;
use JsonSerializable;

interface ReadableCollectionInterface extends IteratorAggregate, Countable, JsonSerializable, MappableInterface
{
    public function findByKey(string $key, mixed $value): ?CollectionItemInterface;
    /**
     * @param callable $fn - function(CollectionItemInterface $item): bool
     * @return CollectionItemInterface|null
     */
    public function find(callable $fn): ?CollectionItemInterface;
    public function column(string $key, string $indexKey = null, callable $fnModifier = null): array;
    /**
     * @param string $key
     * @param callable|null $fn - function(mixed $valueOfKey): mixed
     * @return array
     */
    public function unique(string $key, ?callable $fnModifier = null): array;
    /**
     * @param string $key
     * @return GroupCollectionInterface[]|ReadableCollectionInterface
     */
    public function groupByKey(string $key): ReadableCollectionInterface;
    /**
     * @param string $key
     * @param callable $fnCalcKeyValue - возвращает значение для группировки
     * @return GroupCollectionInterface[]|ReadableCollectionInterface
     */
    public function group(string $key, callable $fnCalcKeyValue): ReadableCollectionInterface;
    /**
     * @param string $key
     * @param ...$value
     * @return CollectionItemInterface[]|ReadableCollectionInterface
     */
    public function filterByKey(string $key, ...$value): ReadableCollectionInterface;
    /**
     * @param callable $fn - attribute CollectionItemInterface
     * @return ReadableCollectionInterface
     */
    public function filter(callable $fn): ReadableCollectionInterface;
    /**
     * @return CollectionItemInterface|null
     */
    public function first(): ?CollectionItemInterface;
}
