<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

final class ArrayArgument extends Node
{
    protected $key;

    protected $value;

    public $negation;

    public function __construct($value, $key = null, $negation = false)
    {
        $this->key = $key;
        $this->value = $value;
        $this->negation = $negation;
    }

    protected function matchNode($phpNode)
    {
        if ($this->key === null) {
            if ($phpNode->key === null) {
                return $this->value->match($phpNode->value);
            } else {
                return false;
            }
        } else {
            if ($phpNode->key === null) {
                return false;
            } else {
                return $this->key->match($phpNode->key)
                    && $this->value->match($phpNode->value);
            }
        }
    }

    protected function getSubNodeNames()
    {
        return ['key', 'value', 'negation'];
    }

    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_ArrayItem';
    }
}
