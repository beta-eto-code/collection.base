<?php

namespace Collection\Base\Tests\Storage;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\Storage\LinkedListStorage;
use PHPUnit\Framework\TestCase;

class LinkedListStorageTest extends TestCase
{
    public function testAppendAndCount()
    {
        $storage = new LinkedListStorage();
        $this->assertCount(0, iterator_to_array($storage->getIterator()));

        $firstItem = new ArrayDataCollectionItem(['id' => 1]);
        $storage->append($firstItem);
        $storage->append(new ArrayDataCollectionItem(['id' => 2]));
        $this->assertCount(2, iterator_to_array($storage->getIterator()));

        $storage->append($firstItem);
        $this->assertCount(3, iterator_to_array($storage->getIterator()));
    }

    public function testGetIterator()
    {
        $storage = new LinkedListStorage();
        $storage->append(new ArrayDataCollectionItem(['id' => 1]));
        $storage->append(new ArrayDataCollectionItem(['id' => 2]));

        $iterator = $storage->getIterator();
        $this->assertTrue($iterator->current()->assertValueByKey('id', 1));

        $iterator->next();
        $this->assertTrue($iterator->current()->assertValueByKey('id', 2));
    }
}


