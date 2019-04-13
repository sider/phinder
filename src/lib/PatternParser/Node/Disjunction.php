<?php

namespace Phinder\PatternParser\Node;

use Phinder\PatternParser\Node;

class Disjunction extends Node
{
    private $_patternNode1;

    private $_patternNode2;

    public function __construct($patternNode1, $patternNode2)
    {
        $this->_patternNode1 = $patternNode1;
        $this->_patternNode2 = $patternNode2;
    }

    public function match($phpNode)
    {
        return $this->_patternNode1->match($phpNode)
        || $this->_patternNode2->match($phpNode);
    }
}
