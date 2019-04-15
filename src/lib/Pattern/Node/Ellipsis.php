<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class Ellipsis extends Node
{
    public function __construct()
    {
    }

    protected function matchPhpNode($phpNode)
    {
        return true;
    }

    protected function getChildrenArray()
    {
        return [];
    }
}
