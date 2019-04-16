<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class IntegerLiteral extends Node
{
    protected static $targetTypes = [
        'Scalar_LNumber',
    ];

    private $_value;

    public function __construct($value = null)
    {
        if ($value === null) {
            $this->_value = null;
        } else {
            $this->_value = (int) $value;
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
