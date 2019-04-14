<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class IntegerLiteral extends Node
{
    private $_value;

    public function __construct($value = null)
    {
        $this->_value = $value;
    }

    public function match($phpNode)
    {
        return true;
    }
}
