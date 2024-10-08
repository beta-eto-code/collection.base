<?php

namespace Collection\Base\Storage;

use Collection\Base\Interfaces\CollectionItemInterface;
use Collection\Base\Interfaces\CollectionStorageInterface;
use EmptyIterator;
use Iterator;

class ArrayStorage implements CollectionStorageInterface
{
    /**
     * @var CollectionItemInterface[]
     */
    protected array $list;
    private bool $needCheckDuplicate;

    public function __construct(bool $needCheckDuplicate = false)
    {
        $this->list = [];
        $this->needCheckDuplicate = $needCheckDuplicate;
    }

    /**
     * @return Iterator|CollectionItemInterface[]
     */
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
        if ($this->needCheckDuplicate) {
            $id = spl_object_id($item);
            $this->list[$id] = $item;
            return;
        }

        $this->list[] = $item;
    }

    public function count(): int
    {
        return count(iterator_to_array($this->getIterator()));
    }
}