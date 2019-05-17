<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

final class Identifier extends Node
{
    private static $_WILDCARDS = ['_', '?'];

    protected $name;

    protected $parent;

    protected $isFullyQualified;

    public function __construct($name, $parent = null)
    {
        $this->isFullyQualified = false;
        $this->name = $name;
        $this->parent = $parent;
    }

    public function makeFullyQualified()
    {
        $this->isFullyQualified = true;

        return $this;
    }

    protected function matchNode($phpNode)
    {
        if (in_array($this->name, self::$_WILDCARDS, true)) {
            return true;
        }

        if ($phpNode->getType() === 'Name' || $phpNode->getType() === 'Name_FullyQualified') {
            return in_array($this->name, $phpNode->parts);
        }

        if ($phpNode->getType() === 'Identifier') {
            return $this->name === $phpNode->name;
        }

        throw new \Exception();
    }

    protected function getSubNodeNames()
    {
        return ['name', 'parent', 'isFullyQualified'];
    }

    protected function isTargetType($phpNodeType)
    {
        return in_array($this->name, self::$_WILDCARDS, true)
            || $phpNodeType === 'Name'
            || $phpNodeType === 'Identifier'
            || $phpNodeType === 'Name_FullyQualified';
    }
}
