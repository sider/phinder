<?php

namespace Phinder\Pattern\Node\LogicalOperation;

use Phinder\Pattern\Node;

final class Negation extends Node
{
    private $_node;

    public function __construct($node)
    {
        $this->_node = $node;
    }

    protected function match($phpNode)
    {
        return !$this->_node->match($phpNode);
    }

    public function toArray()
    {
        return [
            $this->getShortName(),
            $this->_node->toArray(),
        ];
    }
}
