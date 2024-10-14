<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\Interfaces\CollectionStorageInterface;

class StorageAppend extends BaseBenchmark
{
    protected function runBenchmark(CollectionStorageInterface $storage): void
    {
        $size = 1000;
        while ($size-- > 0) {
            $index = 1000 - $size;
            $storage->append(new ArrayDataCollectionItem(['id' => $index, 'value' => 'value' . $index]));
        }
    }
}
