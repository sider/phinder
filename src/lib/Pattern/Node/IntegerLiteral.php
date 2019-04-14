<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class IntegerLiteral extends Node
{
    private $_value;

    public function __construct($value = null)
    {
        if ($value === null) {
            $this->_value = null;
        } else {
            $this->_value = (int) $value;
        }
    }

    public function match($phpNode)
    {
        return true;
    }

    public function getChildrenArray()
    {
        return [$this->_value];
    }
}