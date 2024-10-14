<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\Interfaces\CollectionInterface;

class CollectionColumn extends BaseCollectionBenchmark
{
    protected function runBenchmark(CollectionInterface $collection): void
    {
        $collection->column('value');
    }
}
