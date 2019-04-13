<?php

namespace Phinder\PatternParser\Node;

use Phinder\PatternParser\Node;

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
