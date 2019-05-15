<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

abstract class CaselessConstant extends Node
{
    abstract protected function matchName($name);

    final protected function matchNode($phpNode)
    {
        $name = strtolower($phpNode->name->parts[0]);

        return $this->matchName($name);
    }

    final protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_ConstFetch';
    }
}
