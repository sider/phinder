<?php

namespace Phinder\PatternParser\Node;

use Phinder\PatternParser\Node;

class Invocation extends Node
{
    private $_name;

    private $_arguments;

    public function __construct($name, $arguments)
    {
        $this->_name = $name;
        $this->_arguments = $arguments;
    }

    public function match($phpNode)
    {
        return true;
    }
}
