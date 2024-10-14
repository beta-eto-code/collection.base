<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\Collection;
use Collection\Base\Interfaces\CollectionInterface;
use Collection\Base\Interfaces\CollectionStorageInterface;
use Collection\Base\Storage\ArrayStorage;
use Collection\Base\Storage\IteratorStorage;
use Collection\Base\Storage\LinkedListStorage;
use Collection\Base\Storage\ObjectStorage;
use EmptyIterator;

abstract class BaseCollectionBenchmark
{
    private CollectionInterface $arrayCollection;
    private CollectionInterface $arrayWithCheckCollection;
    private CollectionInterface $iteratorCollection;
    private CollectionInterface $iteratorWithCheckCollection;
    private CollectionInterface $linkedListCollection;
    private CollectionInterface $objectCollection;

    public function __construct()
    {
        $size = 300;
        $this->arrayCollection = $this->createCollection(new ArrayStorage(), $size);
        $this->arrayWithCheckCollection = $this->createCollection(new ArrayStorage(true), $size);
        $this->iteratorCollection = $this->createCollection(new IteratorStorage(new EmptyIterator()), $size);
        $this->iteratorWithCheckCollection = $this->createCollection(
            new IteratorStorage(new EmptyIterator(), true),
            $size
        );
        $this->linkedListCollection = $this->createCollection(new LinkedListStorage(), $size);
        $this->objectCollection = $this->createCollection(new ObjectStorage(), $size);
    }

    abstract protected function runBenchmark(CollectionInterface $collection): void;

    /**
     * @Revs(1000)
     * @Iterations(10)
     */
    public function benchArrayStorage(): void
    {
        $this->runBenchmark($this->arrayCollection);
    }

    /**
     * @Revs(1000)
     * @Iterations(10)
     */
    public function benchArrayStorageWithCheck(): void
    {
        $this->runBenchmark($this->arrayWithCheckCollection);
    }

    /**
     * @Revs(1000)
     * @Iterations(10)
     */
    public function benchIteratorStorage(): void
    {
        $this->runBenchmark($this->iteratorCollection);
    }

    /**
     * @Revs(1000)
     * @Iterations(10)
     */
    public function benchIteratorStorageWithCheck(): void
    {
        $this->runBenchmark($this->iteratorWithCheckCollection);
    }

    /**
     * @Revs(1000)
     * @Iterations(10)
     */
    public function benchLinkedListStorage(): void
    {
        $this->runBenchmark($this->linkedListCollection);
    }

    /**
     * @Revs(1000)
     * @Iterations(10)
     */
    public function benchObjectStorage(): void
    {
        $this->runBenchmark($this->objectCollection);
    }

    protected function createCollection(CollectionStorageInterface $storage, int $size): CollectionInterface
    {
        $this->fillStorage($storage, $size);
        return new Collection([], $storage);
    }

    private function fillStorage(CollectionStorageInterface $storage, int $size): void
    {
        $initSize = $size;
        while ($size-- > 0) {
            $index = $initSize - $size;
            $storage->append(new ArrayDataCollectionItem(['id' => $index, 'value' => 'value' . $index]));
        }
    }
}
