<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class FloatLiteral extends Node
{
    protected static $targetClassNames = ['Scalar\DNumber'];

    private $_value;

    public function __construct($value = null)
    {
        if ($value === null) {
            $this->_value = null;
        } else {
            $this->_value = (float) $value;
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
