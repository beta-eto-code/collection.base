<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\Interfaces\CollectionStorageInterface;

class CollectionFindByKey extends BaseCollectionBenchmark
{
    protected function runBenchmark(CollectionStorageInterface $storage): void
    {
        $this->createCollection($storage, 300)->findByKey('value', 'value122');
    }
}