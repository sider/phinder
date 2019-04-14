<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class StringLiteral extends Node
{
    private $_value;

    public function __construct($value = null)
    {
        if ($value === null) {
            $this->_value = null;
        } else {
            $this->_value = substr($value, 1, strlen($value) - 2);
        }
    }

    protected function matchPhpNode($phpNode)
    {
        return true;
    }

    protected function getChildrenArray()
    {
        return [$this->_value];
    }
}
