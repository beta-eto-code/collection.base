<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\Interfaces\CollectionStorageInterface;

class CollectionFind extends BaseCollectionBenchmark
{
    protected function runBenchmark(CollectionStorageInterface $storage): void
    {
        $this->createCollection($storage, 300)->find(function (ArrayDataCollectionItem $item) {
            return $item->getValueByKey('value') === 'value122';
        });
    }
}
