<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class Identifier extends Node
{
    private $_text;

    public function __construct($text)
    {
        $this->_text = $text;
    }

    protected function match($phpNode)
    {
        return true;
    }

    public function toArray()
    {
        return [
            $this->getShortName(),
            $this->_text,
        ];
    }
}
