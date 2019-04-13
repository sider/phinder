<?php

namespace Phinder\PatternParser\Node;

use Phinder\PatternParser\Node;

class Not extends Node
{
    private $_patternNode;

    public function __construct($patternNode)
    {
        $this->_patternNode = $patternNode;
    }

    public function match($phpNode)
    {
        return !$this->_patternNode->match($phpNode);
    }
}
