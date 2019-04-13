<?php

namespace Phinder\PatternParser\Node;

use Phinder\PatternParser\Node;

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
