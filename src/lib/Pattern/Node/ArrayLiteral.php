<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class ArrayLiteral extends Node
{
    private $_isNewStyle;

    private $_elements;

    public function __construct($isNewStyle, $elements)
    {
        $this->_isNewStyle = $isNewStyle;
        $this->_elements = $elements;
    }

    protected function matchPhpNode($phpNode)
    {
        return true;
    }

    protected function getChildrenArray()
    {
        return [
            $this->_isNewStyle,
            $this->_elements->toArray(),
        ];
    }
}
