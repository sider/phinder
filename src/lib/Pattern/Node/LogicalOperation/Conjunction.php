<?php

namespace Phinder\Pattern\Node\LogicalOperation;

final class Conjunction extends BinaryOperation
{
    protected function match($phpNode)
    {
        return $this->_node1->match($phpNode)
            && $this->_node2->match($phpNode);
    }
}
