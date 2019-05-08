<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

abstract class BinaryOperation extends Node
{
    protected $operand1;

    protected $operand2;

    public function __construct($operand1, $operand2)
    {
        $this->operand1 = $operand1;
        $this->operand2 = $operand2;
    }

    final protected function matchNode($phpNode)
    {
        return $this->operand1->match($phpNode)
            && $this->operand2->match($phpNode);
    }

    final protected function getSubNodeNames()
    {
        return ['operand1', 'operand2'];
    }
}
