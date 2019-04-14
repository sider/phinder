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
}
