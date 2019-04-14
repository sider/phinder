<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class NullLiteral extends Node
{
    public function __construct()
    {
    }

    public function match($phpNode)
    {
        return true;
    }
}
