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
    protected ArrayStorage $arrayStorage;
    protected ArrayStorage $arrayStorageWithCheck;
    protected IteratorStorage $iteratorStorage;
    protected IteratorStorage $iteratorStorageWithCheck;
    protected LinkedListStorage $linkedListStorage;
    protected ObjectStorage $objectStorage;

    public function __construct()
    {
        $this->arrayStorage = new ArrayStorage();
        $this->arrayStorageWithCheck = new ArrayStorage(true);
        $this->iteratorStorage = new IteratorStorage(new EmptyIterator());
        $this->iteratorStorageWithCheck = new IteratorStorage(new EmptyIterator(), true);
        $this->linkedListStorage = new LinkedListStorage();
        $this->objectStorage = new ObjectStorage();
    }

    abstract protected function runBenchmark(CollectionStorageInterface $storage): void;

    /**
     * @Revs(1000)
     * @Iterations(10)
     */
    public function benchArrayStorage(): void
    {
        $this->runBenchmark($this->arrayStorage);
    }

    /**
     * @Revs(1000)
     * @Iterations(10)
     */
    public function benchArrayStorageWithCheck(): void
    {
        $this->runBenchmark($this->arrayStorageWithCheck);
    }

    /**
     * @Revs(1000)
     * @Iterations(10)
     */
    public function benchIteratorStorage(): void
    {
        $this->runBenchmark($this->iteratorStorage);
    }

    /**
     * @Revs(1000)
     * @Iterations(10)
     */
    public function benchIteratorStorageWithCheck(): void
    {
        $this->runBenchmark($this->iteratorStorageWithCheck);
    }

    /**
     * @Revs(1000)
     * @Iterations(10)
     */
    public function benchLinkedListStorage(): void
    {
        $this->runBenchmark($this->linkedListStorage);
    }

    /**
     * @Revs(1000)
     * @Iterations(10)
     */
    public function benchObjectStorage(): void
    {
        $this->runBenchmark($this->objectStorage);
    }
}