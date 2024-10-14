<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\Interfaces\CollectionInterface;

class CollectionFilterByKey extends BaseCollectionBenchmark
{
    protected function runBenchmark(CollectionInterface $collection): void
    {
        $collection->filterBykey('value', 'value22', 'value44', 'value144');
    }
}
