<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class Arguments extends Node
{
    private $_head;

    private $_tail;

    public function __construct($head = null, $tail = null)
    {
        $this->_head = $head;
        $this->_tail = $tail;
    }

    protected function matchPhpNode($phpNode)
    {
        return true;
    }

    protected function getChildrenArray()
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
