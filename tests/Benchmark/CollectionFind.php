<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\Interfaces\CollectionInterface;

class CollectionFind extends BaseCollectionBenchmark
{
    protected function runBenchmark(CollectionInterface $collection): void
    {
        $collection->find(function (ArrayDataCollectionItem $item) {
            return $item->getValueByKey('value') === 'value122';
        });
    }
}
