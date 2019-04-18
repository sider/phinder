<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

abstract class LogicalOperation extends Node
{
    protected function isTargetType($phpNodeType)
    {
        return true;
    }
}
