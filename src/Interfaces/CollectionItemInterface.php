<?php

namespace Collection\Base\Interfaces;

use JsonSerializable;

interface CollectionItemInterface extends JsonSerializable
{
    public function assertValueByKey(string $key, mixed $value): bool;
    public function hasValueKey(string $key): bool;
    public function getValueByKey(string $key);
}
