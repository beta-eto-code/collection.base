<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\Interfaces\CollectionStorageInterface;
use Collection\Base\Storage\ArrayStorage;
use Collection\Base\Storage\IteratorStorage;
use Collection\Base\Storage\LinkedListStorage;
use Collection\Base\Storage\ObjectStorage;
use EmptyIterator;

abstract class BaseBenchmark
{
    abstract protected function runBenchmark(CollectionStorageInterface $storage): void;

    /**
     * @Revs(1000)
     */
    public function benchArrayStorage(): void
    {
        $this->runBenchmark(new ArrayStorage());
    }

    /**
     * @Revs(1000)
     */
    public function benchArrayStorageWithCheck(): void
    {
        $this->runBenchmark(new ArrayStorage(true));
    }

    /**
     * @Revs(1000)
     */
    public function benchIteratorStorage(): void
    {
        $this->runBenchmark(new IteratorStorage(new EmptyIterator()));
    }

    /**
     * @Revs(1000)
     */
    public function benchIteratorStorageWithCheck(): void
    {
        $this->runBenchmark(new IteratorStorage(new EmptyIterator(), true));
    }

    /**
     * @Revs(1000)
     */
    public function benchLinkedListStorage(): void
    {
        $this->runBenchmark(new LinkedListStorage());
    }

    /**
     * @Revs(1000)
     */
    public function benchObjectStorage(): void
    {
        $this->runBenchmark(new ObjectStorage());
    }
}