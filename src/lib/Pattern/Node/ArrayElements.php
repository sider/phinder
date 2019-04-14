<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class ArrayElements extends Node
{
    private $_head;

    private $_tail;

    public function __construct($head = null, $tail = null)
    {
        $this->_head = $head;
        $this->_tail = $tail;
    }

    public function match($phpNode)
    {
        return true;
    }

    public function getChildrenArray()
    {
        $array = [];

        if ($this->_head) {
            $array[] = $this->_head->toArray();
        }

        if ($this->_tail) {
            $array[] = $this->_tail->toArray();
        }

        return $array;
    }
}
