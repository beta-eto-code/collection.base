<?php

namespace Collection\Base\Tests\Storage;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\Storage\IteratorStorage;
use Iterator;
use PHPUnit\Framework\TestCase;

class IteratorStorageTest extends TestCase
{
    public function testAppendAndCount()
    {
        $storage = new IteratorStorage($this->createIterator());
        $this->assertCount(2, iterator_to_array($storage->getIterator()));

        $firstItem = new ArrayDataCollectionItem(['id' => 3]);
        $storage->append($firstItem);
        $storage->append(new ArrayDataCollectionItem(['id' => 4]));
        $this->assertCount(4, iterator_to_array($storage->getIterator()));

        $storage->append($firstItem);
        $this->assertCount(5, iterator_to_array($storage->getIterator()));
    }

    public function testAppendAndCountWithCheckDuplicates()
    {
        $storage = new IteratorStorage($this->createIterator(), true);
        $this->assertCount(2, iterator_to_array($storage->getIterator()));

        $firstItem = new ArrayDataCollectionItem(['id' => 1]);
        $storage->append($firstItem);
        $storage->append(new ArrayDataCollectionItem(['id' => 2]));
        $this->assertCount(4, iterator_to_array($storage->getIterator()));

        $storage->append($firstItem);
        $this->assertCount(4, iterator_to_array($storage->getIterator()));
    }

    public function testGetIterator()
    {
        $storage = new IteratorStorage($this->createIterator());
        $storage->append(new ArrayDataCollectionItem(['id' => 3]));
        $storage->append(new ArrayDataCollectionItem(['id' => 4]));

        $iterator = $storage->getIterator();
        $this->assertTrue($iterator->current()->assertValueByKey('id', 3));

        $iterator->next();
        $this->assertTrue($iterator->current()->assertValueByKey('id', 4));

        $iterator->next();
        $this->assertTrue($iterator->current()->assertValueByKey('id', 1));

        $iterator->next();
        $this->assertTrue($iterator->current()->assertValueByKey('id', 2));
    }

    private function createIterator(): Iterator
    {
        yield new ArrayDataCollectionItem(['id' => 1]);
        yield new ArrayDataCollectionItem(['id' => 2]);
    }
}
