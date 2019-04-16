<?php

namespace Phinder\Pattern\Node\LogicalOperation;

use Phinder\Pattern\Node;

abstract class BinaryOperation extends Node
{
    protected $node1;

    protected $node2;

    final public function __construct($node1, $node2)
    {
        $this->node1 = $node1;
        $this->node2 = $node2;
    }

    final public function toArray()
    {
        return [
            $this->getShortName(),
            $this->node1->toArray(),
            $this->node2->toArray(),
        ];
    }
}
