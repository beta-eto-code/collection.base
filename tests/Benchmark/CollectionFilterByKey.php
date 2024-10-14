<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\Interfaces\CollectionStorageInterface;

class CollectionFilterByKey extends BaseCollectionBenchmark
{
    protected function runBenchmark(CollectionStorageInterface $storage): void
    {
        $this->createCollection($storage, 300)->filterBykey('value', 'value22', 'value44', 'value144');
    }
}
