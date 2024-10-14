<?php

namespace Collection\Base\Tests\Benchmark;

use Collection\Base\ArrayDataCollectionItem;
use Collection\Base\Interfaces\CollectionStorageInterface;

class StorageRemove extends BaseBenchmark
{
    private ArrayDataCollectionItem $chosenItem;

    public function __construct()
    {
        parent::__construct();
        $size = 1000;
        $this->chosenItem = new ArrayDataCollectionItem(['id' => 144, 'value' => 'value' . 144]);
        $this->fillStorage($this->arrayStorage, $size);
        $this->fillStorage($this->arrayStorageWithCheck, $size);
        $this->fillStorage($this->iteratorStorage, $size);
        $this->fillStorage($this->iteratorStorageWithCheck, $size);
        $this->fillStorage($this->linkedListStorage, $size);
        $this->fillStorage($this->objectStorage, $size);
    }

    private function fillStorage(CollectionStorageInterface $storage, int $size): void {
        $initSize = $size;
        while ($size-- > 0) {
            $index = $initSize - $size;
            if ($index === $this->chosenItem->getValueByKey('id')) {
                $storage->append($this->chosenItem);
            }
            $storage->append(new ArrayDataCollectionItem(['id' => $index, 'value' => 'value' . $index]));
        }
    }

    protected function runBenchmark(CollectionStorageInterface $storage): void
    {
        $storage->remove($this->chosenItem);
    }
}
