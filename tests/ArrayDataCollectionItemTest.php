<?php

namespace Collection\Base\Tests;

use PHPUnit\Framework\TestCase;
use Collection\Base\ArrayDataCollectionItem;

class ArrayDataCollectionItemTest extends TestCase
{
    public function testAssertValueByKey()
    {
        $item = new ArrayDataCollectionItem(['key' => 'value']);

        $this->assertTrue($item->assertValueByKey('key', 'value'));
        $this->assertFalse($item->assertValueByKey('key', 'other_value'));
        $this->assertFalse($item->assertValueByKey('other_key', 'value'));
    }

    public function testHasValueKey()
    {
        $item = new ArrayDataCollectionItem(['key' => 'value']);

        $this->assertTrue($item->hasValueKey('key'));
        $this->assertFalse($item->hasValueKey('other_key'));
    }

    public function testGetValueByKey()
    {
        $item = new ArrayDataCollectionItem(['key' => 'value']);

        $this->assertEquals('value', $item->getValueByKey('key'));
        $this->assertNull($item->getValueByKey('other_key'));
    }

    public function testSetValueByKey()
    {
        $item = new ArrayDataCollectionItem(['key' => 'value']);
        $item->setValueByKey('key', 'new value');
        $this->assertEquals('new value', $item->getValueByKey('key'));
    }

    public function testJsonSerialize()
    {
        $item = new ArrayDataCollectionItem(['key' => 'value']);

        $this->assertEquals(['key' => 'value'], $item->jsonSerialize());
    }
}
