<?php

namespace Collection\Base\Tests;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\Collection;
use Collection\Base\Interfaces\CollectionInterface;
use Collection\Base\Interfaces\CollectionItemInterface;
use Exception;
use Iterator;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @var CollectionInterface|CollectionItemInterface[]
     */
    protected CollectionInterface $collection;
    protected array $originalData;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->originalData = [
            [
                'id' => 1,
                'name' => 'name 1',
                'boolean' => true,
            ],
            [
                'id' => 2,
                'name' => 'name 2',
                'boolean' => true,
            ],
            [
                'id' => 3,
                'name' => 'name 3',
                'boolean' => false,
            ],
        ];
        $this->collection = $this->initCollection($this->originalData);
    }

    /**
     * @param array $data
     * @return Collection
     */
    protected function initCollection(array $data): CollectionInterface
    {
        $collection = new Collection();
        foreach ($data as $item) {
            $collection->append(new ArrayDataCollectionItem($item));
        }

        return $collection;
    }


    public function testGroupByKey()
    {
        $result = $this->collection->groupByKey('boolean');
        $this->assertEquals(2, $result->count());

        $trueGroup = $result->findByKey('value', true);
        $this->assertEquals(2, $trueGroup->count());

        $falseGroup = $result->findByKey('value', false);
        $this->assertEquals(1, $falseGroup->count());
    }

    public function testColumn()
    {
        $valuesById = [1, 2, 3];
        $valuesByName = ['name 1', 'name 2', 'name 3'];
        $valuesByBoolean = [true, true, false];
        $this->assertEquals($this->collection->column('id'), $valuesById);
        $this->assertEquals($this->collection->column('name'), $valuesByName);
        $this->assertEquals($this->collection->column('boolean'), $valuesByBoolean);

        $assertValues = ['name 1' => '#1', 'name 2' => '#2', 'name 3' => '#3'];
        $result = $this->collection->column('id', 'name', function (int $id) {
            return "#{$id}";
        });
        $this->assertEquals($result, $assertValues);
    }

    /**
     * @throws Exception
     */
    public function testFirst()
    {
        $this->assertEquals(
            new ArrayDataCollectionItem($this->originalData[0]),
            $this->collection->first()
        );
    }

    public function testFilter()
    {
        $assertValue = $this->initCollection([]);
        foreach ($this->collection as $item) {
            if ($item->assertValueByKey('boolean', true)) {
                $assertValue->append($item);
            }
        }

        $result = $this->collection->filter(function (CollectionItemInterface $item) {
            return $item->assertValueByKey('boolean', true);
        });

        $this->assertEquals($assertValue->jsonSerialize(), $result->jsonSerialize());
    }

    /**
     * @throws Exception
     */
    public function testGetIterator()
    {
        $this->assertTrue(method_exists($this->collection, 'getIterator'));
        $this->assertTrue($this->collection->getIterator() instanceof  Iterator);

        $counter = 0;
        foreach ($this->collection as $i => $model) {
            if (!is_int($i)) {
                continue;
            }

            $counter++;
            foreach ($model as $key => $value) {
                $this->assertEquals($value, $this->originalData[$i][$key]);
            }
        }

        $this->assertCount($counter, $this->originalData);
    }

    public function testFindByKey()
    {
        $assertValue = null;
        foreach ($this->collection as $item) {
            if ($item->assertValueByKey('id', 1)) {
                $assertValue = $item;
                break;
            }
        }

        $this->assertEquals($this->collection->findByKey('id', 1), $assertValue);
    }

    public function testUnique()
    {
        $assertValue = [true, false];
        $result = $this->collection->unique('boolean');
        $this->assertEquals($result, $assertValue);

        $assertValue = ['yes', 'no'];
        $result = $this->collection->unique('boolean', function (bool $boolean) {
           return $boolean ? 'yes' : 'no';
        });
        $this->assertEquals($result, $assertValue);
    }

    public function testJsonSerialize()
    {
        $this->assertEquals($this->collection->jsonSerialize(), $this->originalData);
    }

    public function testFilterByKey()
    {
        $assertValue = $this->initCollection([]);
        foreach ($this->collection as $item) {
            if ($item->getValueByKey('boolean') === true) {
                $assertValue->append($item);
            }
        }

        $result = $this->collection->filterByKey('boolean', true);
        $this->assertEquals($assertValue->jsonSerialize(), $result->jsonSerialize());

        $assertValue = $this->initCollection([]);
        foreach ($this->collection as $item) {
            if (in_array($item->getValueByKey('id'), [1, 2])) {
                $assertValue->append($item);
            }
        }

        $result = $this->collection->filterByKey('id', 1, 2);
        $this->assertEquals($assertValue->jsonSerialize(), $result->jsonSerialize());
    }

    public function testAppend()
    {
        $this->assertEquals(3, $this->collection->count());
        $newModel = new ArrayDataCollectionItem([
            'id' => 4,
            'name' => 'name 4',
            'boolean' => false
        ]);

        $this->collection->append($newModel);
        $lastModel = null;
        foreach ($this->collection as $model) {
            $lastModel = $model;
        }

        $this->assertEquals(4, $this->collection->count());
        $this->assertEquals($lastModel, $lastModel);
    }

    public function testGroup()
    {
        $result = $this->collection->group('tier', function (CollectionItemInterface $item) {
            return $item->getValueByKey('id') < 3 ? 'low' : 'other';
        });

        $this->assertEquals(2, $result->count());

        $lowGroup = $result->findByKey('value', 'low');
        $this->assertEquals(2, $lowGroup->count());

        $otherGroup = $result->findByKey('value', 'other');
        $this->assertEquals(1, $otherGroup->count());
    }

    public function testMap()
    {
        $assertValue = [
            ['id' => '#1'],
            ['id' => '#2'],
            ['id' => '#3'],
        ];
        $result = $this->collection->map(function (CollectionItemInterface $item) {
            return [
                'id' => '#' . $item->getValueByKey('id')
            ];
        });

        $this->assertEquals($assertValue, $result);
    }

    public function testCount()
    {
        $this->assertCount(3, $this->collection);
        $counter = 0;
        foreach ($this->collection as $model) {
            $counter++;
        }

        $this->assertEquals(3, $counter);
    }

    public function testFind()
    {
        $assertValue = null;
        foreach ($this->collection as $item) {
            if ($item->assertValueByKey('id', 1)) {
                $assertValue = $item;
                break;
            }
        }

        $result = $this->collection->find(function (CollectionItemInterface $item) {
            return $item->assertValueByKey('id', 1);
        });

        $this->assertEquals($result, $assertValue);
    }
}
