<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

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

    public function getChildrenArray()
    {
        return [$this->_varlen];
    }
}
