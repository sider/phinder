<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

final class Identifier extends Node
{
    private static $_WILDCARDS = ['_', '?'];

    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    protected function matchNode($phpNode)
    {
        if (in_array($this->name, self::$_WILDCARDS, true)) {
            return true;
        }

        if ($phpNode->getType() === 'Name') {
            return in_array($this->name, $phpNode->parts);
        }

        if ($phpNode->getType() === 'Identifier') {
            return $this->name === $phpNode->name;
        }

        throw new \Exception();
    }

    protected function getSubNodeNames()
    {
        return ['name'];
    }

    protected function isTargetType($phpNodeType)
    {
        return in_array($this->name, self::$_WILDCARDS, true)
            || $phpNodeType === 'Name'
            || $phpNodeType === 'Identifier';
    }
}
