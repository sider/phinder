<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

final class Arguments extends Node
{
    private $_head;

    private $_tail;

    public function __construct($head = null, $tail = null)
    {
        $this->_head = $head;
        $this->_tail = $tail;
    }

    protected function match($phpNode)
    {
        return true;
    }

    public function toArray()
    {
        $array = [$this->getShortName()];

        if ($this->_head !== null) {
            $array[] = $this->_head->toArray();
        }

        if ($this->_tail !== null) {
            $array[] = $this->_tail->toArray();
        }

        return $array;
    }
}
