<?php

namespace Collection\Base\Tests;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\GroupCollection;
use Collection\Base\Interfaces\CollectionInterface;

class GroupCollectionTest extends CollectionTest
{
    protected function initCollection(array $data): CollectionInterface
    {
        $collection = new GroupCollection('test', 'one');
        foreach ($data as $item) {
            $collection->append(new ArrayDataCollectionItem($item));
        }

        return $collection;
    }

    public function testJsonSerialize()
    {
        $assertValue = [
            'key' => 'test',
            'value' => 'one',
            'list' => $this->originalData,
        ];

        $this->assertEquals($this->collection->jsonSerialize(), $assertValue);
    }
}
