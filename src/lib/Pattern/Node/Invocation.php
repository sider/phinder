<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

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

    public function getChildrenArray()
    {
        return [
            $this->_name,
            $this->_arguments->toArray(),
        ];
    }
}
