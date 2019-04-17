<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

abstract class Scalar extends Node
{
    protected $value;

    abstract protected static function preprocess($text);

    final public function __construct($text = null)
    {
        $this->value = $text === null ? $text : static::preprocess($text);
    }

    final protected function matchNode($phpNode)
    {
        return $this->value === null || $this->value === $phpNode->value;
    }

    final protected function getSubNodeNames()
    {
        return ['value'];
    }
}
