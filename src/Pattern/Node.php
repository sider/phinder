<?php

namespace Phinder\Pattern;

use PhpParser\NodeTraverser as PhpNodeTraverser;
use PhpParser\Node as PhpNode;
use PhpParser\NodeVisitor as PhpNodeVisitor;

abstract class Node implements PhpNodeVisitor
{
    const ELLIPSIS = 'ELLIPSIS';

    abstract protected function matchNode($phpNode);

    abstract protected function getSubNodeNames();

    abstract protected function isTargetType($phpNodeType);

    private $_matches;

    final public function match($phpNode)
    {
        return $this->isTargetType($phpNode->getType())
            && $this->matchNode($phpNode);
    }

    final public function visit($phpNode)
    {
        $this->_matches = [];

        $traverser = new PhpNodeTraverser();
        $traverser->addVisitor($this);
        $traverser->traverse($phpNode);

        return $this->_matches;
    }

    final public function beforeTraverse(array $nodes)
    {
        return null;
    }

    final public function enterNode(PhpNode $node)
    {
        if ($this->match($node)) {
            $this->_matches[] = $node;
        }

        return null;
    }

    final public function leaveNode(PhpNode $node)
    {
        return null;
    }

    final public function afterTraverse(array $nodes)
    {
        return null;
    }

    final public function toArray()
    {
        $array = [(new \ReflectionClass($this))->getShortName()];

        foreach ($this->getSubNodeNames() as $name) {
            $subNode = $this->$name;
            if (is_array($subNode)) {
                $array[] = array_map(
                    function ($n) {
                        if (is_subclass_of($n, 'Phinder\Pattern\Node')) {
                            return $n->toArray();
                        } else {
                            return $n;
                        }
                    },
                    $subNode
                );
            } elseif (is_subclass_of($subNode, 'Phinder\Pattern\Node')) {
                $array[] = $subNode->toArray();
            } else {
                $array[] = $subNode;
            }
        }

        return $array;
    }
}
