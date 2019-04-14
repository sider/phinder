<?php

namespace Phinder\Pattern;

abstract class Node
{
    abstract public function match($phpNode);

    abstract protected function getChildrenArray();

    final public function toArray()
    {
        $array = $this->getChildrenArray();
        $name = $reflect = (new \ReflectionClass($this))->getShortName();
        array_unshift($array, $name);

        return $array;
    }
}
