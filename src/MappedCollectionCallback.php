<?php

namespace Collection\Base;


use Collection\Base\Interfaces\CollectionItemInterface;
use Collection\Base\Interfaces\ReadableCollectionInterface;

class MappedCollectionCallback extends MappedCollection
{
    /**
     * @var CollectionItemInterface[]
     */
    protected array $list;
    /**
     * @var callable
     */
    private $fn;

    public function __construct(iterable $itemList, callable $fn)
    {
        $this->list = [];
        $this->fn = $fn;

        foreach ($itemList as $item) {
            if ($item instanceof CollectionItemInterface) {
                $this->append($item);
            }
        }
    }

    /**
     * @param callable $fn - attribute CollectionItemInterface
     * @return ReadableCollectionInterface
     */
    public function filter(callable $fn): ReadableCollectionInterface
    {
        return new static(array_filter($this->list, $fn), $this->fn);
    }

    /**
     * @param CollectionItemInterface $item
     */
    public function append(CollectionItemInterface $item): void
    {
        $indexKey = ($this->fn)($item);
        $this->list[$indexKey] = $item;
    }

    /**
     * @param CollectionItemInterface $item
     */
    public function remove(CollectionItemInterface $item)
    {
        $indexKey = ($this->fn)($item);
        unset($this->list[$indexKey]);
    }
}
