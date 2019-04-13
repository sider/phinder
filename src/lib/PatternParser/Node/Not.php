<?php

namespace Phinder\PatternParser\Node;

class Not extends AbstractNode
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
