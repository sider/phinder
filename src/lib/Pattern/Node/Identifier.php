<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class Identifier extends Node
{
    protected static $targetTypes = [
        'Name',
    ];

    private $_text;

    public function __construct($text)
    {
        $this->_text = $text;
    }

    protected function match($phpNode)
    {
        return $this->_text === null
            || in_array($this->_text, $phpNode->parts);
    }

    public function toArray()
    {
        return [
            $this->getShortName(),
            $this->_text,
        ];
    }
}
