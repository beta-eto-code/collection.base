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

        if (empty($this->iterator)) {
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
}
