<?php

namespace Collection\Base\Interfaces;

use Countable;
use Iterator;
use IteratorAggregate;

interface CollectionStorageInterface extends IteratorAggregate, Countable
{
    /**
     * @return Iterator|CollectionItemInterface[]
     */
    public function getIterator(): Iterator;
    public function append(CollectionItemInterface $item): void;
}
