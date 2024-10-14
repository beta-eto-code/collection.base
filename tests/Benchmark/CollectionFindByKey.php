<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\Interfaces\CollectionInterface;

class CollectionFindByKey extends BaseCollectionBenchmark
{
    protected function runBenchmark(CollectionInterface $collection): void
    {
        $collection->findByKey('value', 'value122');
    }
}