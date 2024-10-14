<?php

namespace Collection\Base\Tests;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\GroupCollection;
use Collection\Base\Interfaces\CollectionInterface;

class GroupCollectionTest extends CollectionTest
{
    protected function createCollection(): CollectionInterface
    {
        return new GroupCollection('test', 'one');
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
