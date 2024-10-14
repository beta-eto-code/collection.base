<?php

namespace Collection\Base\Tests;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\GroupCollection;
use Collection\Base\Interfaces\CollectionInterface;
use Collection\Base\Interfaces\CollectionItemInterface;
use Collection\Base\MappedCollectionCallback;
use Iterator;

class MappedCollectionCallbackTest extends CollectionTest
{
    protected function createCollection(): CollectionInterface
    {
        return new MappedCollectionCallback([], function (CollectionItemInterface $item) {
            return '#' . $item->getValueByKey('id');
        });
    }

    public function testMap()
    {
        $assertValue = [
            '#1' => ['id' => '#1'],
            '#2' => ['id' => '#2'],
            '#3' => ['id' => '#3'],
        ];
        $result = $this->collection->map(function (CollectionItemInterface $item) {
            return [
                'id' => '#' . $item->getValueByKey('id')
            ];
        });

        $this->assertEquals($result, $assertValue);
    }

    public function testGetIterator()
    {
        $this->assertTrue(method_exists($this->collection, 'getIterator'));
        $this->assertTrue($this->collection->getIterator() instanceof Iterator);

        $counter = 0;
        foreach ($this->collection as $model) {
            foreach ($model as $key => $value) {
                $this->assertEquals($value, $this->originalData[$counter][$key]);
            }
            $counter++;
        }

        $this->assertCount($counter, $this->originalData);
    }

    public function testOffsetExists()
    {
        $this->assertTrue(isset($this->collection['#1']));
        $this->assertTrue($this->collection->offsetExists('#1'));

        $this->assertFalse(isset($this->collection['#6']));
        $this->assertFalse($this->collection->offsetExists('#6'));
    }

    public function testOffsetGet()
    {
        $assertValue = null;
        foreach ($this->collection as $item) {
            if ($item->assertValueByKey('id', 1)) {
                $assertValue = $item;
                break;
            }
        }

        $this->assertEquals($this->collection['#1'], $assertValue);
        $this->assertEquals($this->collection->offsetGet('#1'), $assertValue);
    }

    public function testOffsetSet()
    {
        $newModel = new ArrayDataCollectionItem([
            'id' => 5,
            'name' => 'name ',
            'boolean' => false,
        ]);

        $this->assertFalse($this->collection->offsetExists('#5'));
        $this->collection->append($newModel);
        $this->assertTrue($this->collection->offsetExists('#5'));
        $this->assertEquals($this->collection['#5'], $newModel);
    }

    public function testOffsetUnset()
    {
        $this->assertTrue($this->collection->offsetExists('#2'));
        unset($this->collection['#2']);
        $this->assertFalse($this->collection->offsetExists('#2'));
    }
}
