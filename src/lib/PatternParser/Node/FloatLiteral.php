<?php

namespace Phinder\PatternParser\Node;

class FloatLiteral extends AbstractNode
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
