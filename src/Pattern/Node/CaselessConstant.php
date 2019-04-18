<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

abstract class CaselessConstant extends Node
{
    protected $name;

    public function __construct($name = null)
    {
        $this->name = $name === null ? null : $name;
    }

    final protected function matchNode($phpNode)
    {
        $name = strtolower($phpNode->name->parts[0]);

        return $this->name === null || $this->name === $name;
    }

    final protected function getSubNodeNames()
    {
        return ['name'];
    }

    final protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_ConstFetch';
    }
}
