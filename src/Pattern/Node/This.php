<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

final class This extends Node
{
    protected function matchNode($phpNode)
    {
        return $phpNode->name === 'this';
    }

    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_Variable';
    }

    protected function getSubNodeNames()
    {
        return [];
    }
}
