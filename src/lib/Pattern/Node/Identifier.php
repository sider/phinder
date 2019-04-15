<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class Identifier extends Node
{
    protected static $targetClassNames = ['Name'];

    private $_text;

    public function __construct($text)
    {
        $this->_text = $text;
    }

    protected function matchPhpNode($phpNode)
    {
        return true;
    }

    protected function getChildrenArray()
    {
        return [$this->_text];
    }
}
