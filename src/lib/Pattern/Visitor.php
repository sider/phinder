<?php

namespace Phinder\Pattern;

use PhpParser\NodeTraverser as PhpNodeTraverser;
use PhpParser\NodeVisitor as PhpNodeVisitor;
use PhpParser\Node as PhpNode;

class Visitor implements PhpNodeVisitor
{
    public $nodes;

    private $_targetTypes;

    public function __construct($targetTypes)
    {
        $this->_targetTypes = $targetTypes;
        $this->nodes = [];
    }

    public function enterNode(PhpNode $node)
    {
        if ($this->_isTargetNode($node)) {
            $this->nodes[] = $node;

            return PhpNodeTraverser::DONT_TRAVERSE_CHILDREN;
        }

        return null;
    }

    public function beforeTraverse(array $nodes)
    {
        return null;
    }

    public function leaveNode(PhpNode $node)
    {
        return null;
    }

    public function afterTraverse(array $nodes)
    {
        return null;
    }

    private function _isTargetNode($node)
    {
        return $this->_targetTypes === null
            || in_array($node->getType(), $this->_targetTypes, true);
    }
}
