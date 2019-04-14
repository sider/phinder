<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class StringLiteral extends Node
{
    protected static $targetClassNames = ['Scalar\String_'];

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
        if ($this->_value === null) {
            return true;
        }

        return $this->_value === $phpNode->value;
    }

    protected function getChildrenArray()
    {
        return [$this->_value];
    }
}
