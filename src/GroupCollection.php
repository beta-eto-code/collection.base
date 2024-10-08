<?php

namespace Collection\Base;

use Collection\Base\Interfaces\CollectionItemInterface;
use Collection\Base\Interfaces\CollectionStorageInterface;
use Collection\Base\Interfaces\GroupCollectionInterface;
use Collection\Base\Interfaces\ReadableCollectionInterface;

class GroupCollection extends Collection implements GroupCollectionInterface
{
    private mixed $key;
    private mixed $value;

    public function __construct(
        mixed $key,
        mixed $value,
        iterable $itemList = [],
        ?CollectionStorageInterface $storage = null
    ) {
        $this->key = $key;
        $this->value = $value;
        parent::__construct($itemList, $storage);
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
        return new static($this->key, $this->value, $itemList, $storage);
    }

    public function getKey(): mixed
    {
        return $this->key;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function assertValueByKey(string $key, mixed $value): bool
    {
        return $this->getValueByKey($key) == $value;
    }

    public function hasValueKey(string $key): bool
    {
        return in_array($key, ['key', 'value']);
    }

    public function getValueByKey(string $key): mixed
    {
        if ($key === 'key') {
            return $this->key;
        }

        if ($key === 'value') {
            return $this->value;
        }

        return null;
    }

    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
            'list' => parent::jsonSerialize(),
        ];
    }
}
