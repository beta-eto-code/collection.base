<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\Interfaces\CollectionInterface;

class CollectionUnique extends BaseCollectionBenchmark
{
    protected function runBenchmark(CollectionInterface $collection): void
    {
        $collection->unique('value');
    }
}
