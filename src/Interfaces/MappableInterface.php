<?php

namespace Collection\Base\Interfaces;

interface MappableInterface
{
    /**
     * @param callable $fnMap - function($item): array
     * @return mixed
     */
    public function map(callable $fnMap): mixed;
}
