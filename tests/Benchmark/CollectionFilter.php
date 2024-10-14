<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\Interfaces\CollectionStorageInterface;

class CollectionFilter extends BaseCollectionBenchmark
{
    protected function runBenchmark(CollectionStorageInterface $storage): void
    {
        $this->createCollection($storage, 300)->filter(function (ArrayDataCollectionItem $item) {
            return in_array($item->getValueByKey('value'), ['value22', 'value44', 'value144']);
        });
    }
}
