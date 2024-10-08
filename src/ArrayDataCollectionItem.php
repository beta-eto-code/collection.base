<?php

namespace Collection\Base;

use Collection\Base\Interfaces\CollectionItemInterface;

class ArrayDataCollectionItem implements CollectionItemInterface
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function assertValueByKey(string $key, mixed $value): bool
    {
        return $this->hasValueKey($key) && $this->data[$key] === $value;
    }

    public function hasValueKey(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function getValueByKey(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
