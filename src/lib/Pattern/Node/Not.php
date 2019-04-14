<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

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
