<?php

namespace Phinder\PatternParser\Node;

class Wildcard extends AbstractNode
{
    private $_varlen;

    public function __construct($varlen = false)
    {
        $this->_varlen = $varlen;
    }

    public function match($phpNode)
    {
        return true;
    }
}
