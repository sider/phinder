<?php

namespace Phinder\PatternParser\Node;

class Wildcard extends AbstractNode
{
    public function match($phpNode)
    {
        return true;
    }
}
