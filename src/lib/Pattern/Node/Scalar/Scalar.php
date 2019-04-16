<?php

namespace Phinder\Pattern\Node\Scalar;

use Phinder\Pattern\Node;

abstract class Scalar extends Node
{
    abstract protected static function preprocess($text);

    private $_value;

    final public function __construct($text = null)
    {
        $this->_value = $text === null ? $text : static::preprocess($text);
    }

    final protected function match($phpNode)
    {
        return $this->_value === null || $this->_value === $phpNode->value;
    }

    final public function toArray()
    {
        return [
            $this->getShortName(),
            $this->_value,
        ];
    }
}
