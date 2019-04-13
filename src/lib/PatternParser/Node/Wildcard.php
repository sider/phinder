<?php

namespace Phinder\PatternParser\Node;

use Phinder\PatternParser\Node;

class Wildcard extends Node
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
