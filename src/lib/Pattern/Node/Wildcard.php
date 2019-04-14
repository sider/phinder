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

    protected function matchPhpNode($phpNode)
    {
        return true;
    }

    protected function getChildrenArray()
    {
        return [$this->_varlen];
    }
}
