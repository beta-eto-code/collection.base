<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\Interfaces\CollectionInterface;

class CollectionFilter extends BaseCollectionBenchmark
{
    protected function runBenchmark(CollectionInterface $collection): void
    {
        $collection->filter(function (ArrayDataCollectionItem $item) {
            return in_array($item->getValueByKey('value'), ['value22', 'value44', 'value144']);
        });
    }
}
