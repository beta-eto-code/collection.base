<?php

namespace Collection\Base\Tests\Storage;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\Storage\ObjectStorage;
use PHPUnit\Framework\TestCase;

class ObjectStorageTest extends TestCase
{
    public function testAppendAndCount()
    {
        $storage = new ObjectStorage();
        $this->assertCount(0, iterator_to_array($storage->getIterator()));

        $firstItem = new ArrayDataCollectionItem(['id' => 1]);
        $storage->append($firstItem);
        $storage->append(new ArrayDataCollectionItem(['id' => 2]));
        $this->assertCount(2, iterator_to_array($storage->getIterator()));

        $storage->append($firstItem);
        $this->assertCount(2, iterator_to_array($storage->getIterator()));
    }

    public function testRemove()
    {
        $storage = new ObjectStorage();
        $firstItem = new ArrayDataCollectionItem(['id' => 1]);
        $storage->append($firstItem);

        $secondItem = new ArrayDataCollectionItem(['id' => 2]);
        $storage->append($secondItem);
        $this->assertCount(2, iterator_to_array($storage->getIterator()));

        $storage->remove(new ArrayDataCollectionItem(['id' => 1]));
        $this->assertCount(2, iterator_to_array($storage->getIterator()));

        $storage->remove($firstItem);
        $this->assertCount(1, iterator_to_array($storage->getIterator()));

        $storage->remove(new ArrayDataCollectionItem(['id' => 2]));
        $this->assertCount(1, iterator_to_array($storage->getIterator()));

        $storage->remove($secondItem);
        $this->assertEmpty(iterator_to_array($storage->getIterator()));
    }

    public function testGetIterator()
    {
        $storage = new ObjectStorage();
        $storage->append(new ArrayDataCollectionItem(['id' => 1]));
        $storage->append(new ArrayDataCollectionItem(['id' => 2]));

        $iterator = $storage->getIterator();
        $this->assertTrue($iterator->current()->assertValueByKey('id', 1));

        $iterator->next();
        $this->assertTrue($iterator->current()->assertValueByKey('id', 2));
    }
}
