<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

final class Argument extends Node
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    protected function matchNode($phpNode)
    {
        return $this->value->match($phpNode->value);
    }

    protected function getSubNodeNames()
    {
        return ['value'];
    }

    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Arg';
    }
}
