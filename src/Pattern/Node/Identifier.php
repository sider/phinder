<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

final class Identifier extends Node
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    protected function matchNode($phpNode)
    {
        if ($this->name === '_') {
            return true;
        }

        if ($phpNode->getType() === 'Name') {
            return in_array($this->name, $phpNode->parts);
        }

        if ($phpNode->getType() === 'Identifier') {
            return $this->name === $phpNode->name;
        }

        // @codeCoverageIgnoreStart
        throw new \Exception();
        // @codeCoverageIgnoreEnd
    }

    protected function getSubNodeNames()
    {
        return ['name'];
    }

    protected function isTargetType($phpNodeType)
    {
        return $this->name === '_'
            || $phpNodeType === 'Name'
            || $phpNodeType === 'Identifier';
    }
}
