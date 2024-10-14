<?php

namespace Collection\Base\Storage;

use Collection\Base\Interfaces\CollectionItemInterface;
use Collection\Base\Interfaces\CollectionStorageInterface;
use EmptyIterator;
use Iterator;
use SplDoublyLinkedList;

class LinkedListStorage implements CollectionStorageInterface
{
    private SplDoublyLinkedList $list;

    public function __construct()
    {
        $this->list = new SplDoublyLinkedList();
    }

    public function getIterator(): Iterator
    {
        if (empty($this->list)) {
            return new EmptyIterator();
        }

        foreach ($this->list as $item) {
            yield $item;
        }
    }

    public function append(CollectionItemInterface $item): void
    {
        $this->list->push($item);
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

    public function count(): int
    {
        return $this->list->count();
    }
}
