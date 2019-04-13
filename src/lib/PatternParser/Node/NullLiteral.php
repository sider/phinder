<?php

namespace Phinder\PatternParser\Node;

class NullLiteral extends AbstractNode
{
    public function __construct()
    {
    }

    public function match($phpNode)
    {
        return true;
    }
}
