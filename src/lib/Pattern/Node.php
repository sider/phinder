<?php

namespace Phinder\Pattern;

use PhpParser\NodeTraverser as PhpNodeTraverser;

abstract class Node
{
    protected static $targetTypes = null;

    final public function __invoke($phpNode)
    {
        $traverser = new PhpNodeTraverser();
        $visitor = new Visitor(static::$targetTypes);

        $traverser->addVisitor($visitor);
        $traverser->traverse($phpNode);

        foreach ($visitor->nodes as $node) {
            if ($this->match($node)) {
                return true;
            }
        }

        return false;
    }

    public function toArray()
    {
        return [$this->getShortName()];
    }

    protected function match($phpNode)
    {
        return false;
    }

    final protected function getShortName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
