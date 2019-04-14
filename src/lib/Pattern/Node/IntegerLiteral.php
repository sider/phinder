<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class IntegerLiteral extends Node
{
    protected static $targetClassNames = ['Scalar\LNumber'];

    private $_value;

    public function __construct($value = null)
    {
        if ($value === null) {
            $this->_value = null;
        } else {
            $this->_value = (int) $value;
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
