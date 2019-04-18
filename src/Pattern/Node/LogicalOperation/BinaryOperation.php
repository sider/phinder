<?php

namespace Phinder\Pattern\Node\LogicalOperation;

use Phinder\Pattern\Node\LogicalOperation;

abstract class BinaryOperation extends LogicalOperation
{
    protected $node1;

    protected $node2;

    final public function __construct($node1, $node2)
    {
        $this->node1 = $node1;
        $this->node2 = $node2;
    }

    final protected function getSubNodeNames()
    {
        return ['node1', 'node2'];
    }
}
