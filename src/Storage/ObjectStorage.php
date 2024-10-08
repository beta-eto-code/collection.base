<?php

namespace Collection\Base\Storage;

use Collection\Base\Interfaces\CollectionItemInterface;
use Collection\Base\Interfaces\CollectionStorageInterface;
use EmptyIterator;
use Iterator;
use SplObjectStorage;

class ObjectStorage implements CollectionStorageInterface
{
    private SplObjectStorage $list;

    public function __construct()
    {
        $this->list = new SplObjectStorage();
    }

    public function getIterator(): Iterator
    {
        if (empty($this->list)) {
            return new EmptyIterator();
        }

        foreach ($this->list as $object) {
            yield $object;
        }
    }

    public function append(CollectionItemInterface $item): void
    {
        $this->list->attach($item);
    }

    public function count(): int
    {
        return $this->list->count();
    }
}
