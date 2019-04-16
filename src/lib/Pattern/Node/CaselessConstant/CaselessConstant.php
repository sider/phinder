<?php

namespace Phinder\Pattern\Node\CaselessConstant;

use Phinder\Pattern\Node;

abstract class CaselessConstant extends Node
{
    protected static $targetTypes = [
        'Expr_ConstFetch',
    ];

    private $_name;

    public function __construct($name = null)
    {
        $this->_name = $name === null ? null : $name;
    }

    final protected function match($phpNode)
    {
        $name = strtolower($phpNode->name->parts[0]);

        return $this->_name === null || $this->_name === $name;
    }

    final public function toArray()
    {
        return [
            $this->getShortName(),
            $this->_name,
        ];
    }
}
