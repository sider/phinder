<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class Identifier extends Node
{
    private $_text;

    public function __construct($text)
    {
        $this->_text = $text;
    }

    public function match($phpNode)
    {
        return true;
    }

    public function getChildrenArray()
    {
        return [$this->_text];
    }
}
