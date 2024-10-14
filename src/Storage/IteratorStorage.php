<?php

namespace Collection\Base\Storage;

use Collection\Base\Interfaces\CollectionItemInterface;
use EmptyIterator;
use Iterator;

class IteratorStorage extends ArrayStorage
{
    private ?Iterator $iterator;

    public function __construct(Iterator $iterator, bool $needCheckDuplicate = false)
    {
        parent::__construct($needCheckDuplicate);
        $this->iterator = $iterator;
    }

    /**
     * @return Iterator|CollectionItemInterface[]
     */
    public function getIterator(): Iterator
    {
        foreach ($this->list as $item) {
            yield $item;
        }

        if (is_null($this->iterator) || !$this->iterator->valid()) {
            return new EmptyIterator();
        }

        foreach ($this->iterator as $item) {
            if ($item instanceof CollectionItemInterface) {
                yield $item;
                $this->append($item);
            }
        }
        $this->iterator = null;
        return new EmptyIterator();
    }

    public function remove(CollectionItemInterface $item): void
    {
        $this->loadItemsFromIterator();
        parent::remove($item);
    }

    private function loadItemsFromIterator(): void
    {
        if (is_null($this->iterator) || !$this->iterator->valid()) {
            return;
        }

        foreach ($this->getIterator() as $item) {}
    }
}
