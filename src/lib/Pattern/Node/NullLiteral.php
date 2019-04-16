<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class NullLiteral extends Node
{
    protected static $targetTypes = [
        'Expr_ConstFetch',
    ];

    protected function match($phpNode)
    {
        $value = strtolower($phpNode->name->parts[0]);

        return $value === 'null';
    }
}
