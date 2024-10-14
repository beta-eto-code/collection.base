<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\Collection;
use Collection\Base\Interfaces\CollectionInterface;
use Collection\Base\Interfaces\CollectionStorageInterface;

abstract class BaseCollectionBenchmark extends BaseBenchmark
{
    protected function createCollection(CollectionStorageInterface $storage, int $size): CollectionInterface
    {
        $this->fillStorage($storage, $size);
        return new Collection([], $storage);
    }

    private function fillStorage(CollectionStorageInterface $storage, int $size): void
    {
        while ($size-- > 0) {
            $index = 1000 - $size;
            $storage->append(new ArrayDataCollectionItem(['id' => $index, 'value' => 'value' . $index]));
        }
    }
}
