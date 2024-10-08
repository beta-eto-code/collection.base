<?php

namespace Collection\Base\Interfaces;

interface GroupCollectionInterface extends CollectionInterface, CollectionItemInterface
{
    public function getKey(): mixed;
    public function getValue(): mixed;
}
