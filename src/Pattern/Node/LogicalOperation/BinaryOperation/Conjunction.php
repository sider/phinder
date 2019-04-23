<?php

namespace Phinder\Pattern\Node\LogicalOperation\BinaryOperation;

use Phinder\Pattern\Node\LogicalOperation\BinaryOperation;

final class Conjunction extends BinaryOperation
{
    protected function matchNode($phpNode)
    {
        return $this->node1->match($phpNode)
            && $this->node2->match($phpNode);
    }
}
