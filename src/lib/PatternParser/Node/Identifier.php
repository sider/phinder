<?php

namespace Phinder\PatternParser\Node;

class Identifier extends AbstractNode
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
