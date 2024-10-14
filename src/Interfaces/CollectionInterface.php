<?php

namespace Collection\Base\Interfaces;

interface CollectionInterface extends ReadableCollectionInterface
{
    public function append(CollectionItemInterface $item): void;
    public function remove(CollectionItemInterface $item): void;
    public function newCollection(
        iterable $itemList = [],
        ?CollectionStorageInterface $storage = null
    ): ReadableCollectionInterface;
}
