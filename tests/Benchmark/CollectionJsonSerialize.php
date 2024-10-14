<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\Interfaces\CollectionInterface;

class CollectionJsonSerialize extends BaseCollectionBenchmark
{
    protected function runBenchmark(CollectionInterface $collection): void
    {
        $collection->jsonSerialize();
    }
}
