<?php

namespace Phinder\PatternParser\Node;

class Arguments extends AbstractNode
{
    private $_head;

    private $_tail;

    public function __construct($head = null, $tail = null)
    {
        $this->_head = $head;
        $this->_tail = $tail;
    }

    public function match($phpNode)
    {
        return true;
    }
}
