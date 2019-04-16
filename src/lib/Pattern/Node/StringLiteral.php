<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class StringLiteral extends Node
{
    protected static $targetTypes = [
        'Scalar_String',
    ];

    public function __construct($value = null)
    {
        if ($value === null) {
            $this->_value = null;
        } else {
            $this->_value = substr($value, 1, strlen($value) - 2);
        }
    }

    protected function match($phpNode)
    {
        if ($this->_value === null) {
            return true;
        }

        return $this->_value === $phpNode->value;
    }

    public function toArray()
    {
        return [
            $this->getShortName(),
            $this->_value,
        ];
    }
}
