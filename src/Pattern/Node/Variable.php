<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

final class Variable extends Node
{
    protected $name;

    public function __construct($name)
    {
        $this->name = substr($name, 1);
    }

    protected function matchNode($phpNode)
    {
        return $this->name === $phpNode->name;
    }

    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_Variable';
    }

    protected function getSubNodeNames()
    {
        return ['name'];
    }
}
