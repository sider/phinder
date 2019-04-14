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

    protected function matchPhpNode($phpNode)
    {
        return !$this->_patternNode->match($phpNode);
    }

    protected function getChildrenArray()
    {
        return [$this->_patternNode->toArray()];
    }
}
