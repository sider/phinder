<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class NullLiteral extends Node
{
    protected static $targetClassNames = ['Expr\ConstFetch'];

    public function __construct()
    {
    }

    protected function matchPhpNode($phpNode)
    {
        $value = strtolower($phpNode->name->parts[0]);

        return $value === 'null';
    }

    protected function getChildrenArray()
    {
        return [];
    }
}
