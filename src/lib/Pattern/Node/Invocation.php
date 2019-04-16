<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class Invocation extends Node
{
    private $_identifier;

    private $_arguments;

    public function __construct($identifier, $arguments)
    {
        $this->_identifier = $identifier;
        $this->_arguments = $arguments;
    }

    public function toArray()
    {
        return [
            $this->getShortName(),
            $this->_identifier->toArray(),
            $this->_arguments->toArray(),
        ];
    }
}
