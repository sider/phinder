<?php

namespace Phinder\Pattern\Node\LogicalOperation;

use Phinder\Pattern\Node\LogicalOperation;

final class Negation extends LogicalOperation
{
    protected $node;

    public function __construct($node)
    {
        $this->node = $node;
    }

    protected function matchNode($phpNode)
    {
        return !$this->node->visit([$phpNode]);
    }

    protected function getSubNodeNames()
    {
        return ['node'];
    }
}
