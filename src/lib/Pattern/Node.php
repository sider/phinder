<?php

namespace Phinder\Pattern;

use PhpParser\NodeTraverser as PhpNodeTraverser;
use PhpParser\NodeVisitorAbstract as PhpNodeVisitorAbstract;

abstract class Node
{
    protected static $targetClassNames = [];

    abstract protected function matchPhpNode($phpNode);

    abstract protected function getChildrenArray();

    final public function toArray()
    {
        $array = $this->getChildrenArray();
        $name = $reflect = (new \ReflectionClass($this))->getShortName();
        array_unshift($array, $name);

        return $array;
    }

    final public function match($phpNode)
    {
        $traverser = new PhpNodeTraverser();
        $visitor = self::_createVisitor();

        $traverser->addVisitor($visitor);
        $traverser->traverse($phpNode);

        foreach ($visitor->targetNodes as $node) {
            if ($this->matchPhpNode($node)) {
                return true;
            }
        }

        return false;
    }

    private static function _createVisitor()
    {
        return new class(static::$targetClassNames) extends PhpNodeVisitorAbstract {
            public $targetNodes;

            private $_targetClassNames;

            public function __construct($targetClassNames)
            {
                $this->targetNodes = [];
                $this->_targetClassNames = [];

                foreach ($targetClassNames as $name) {
                    $this->_targetClassNames[] = "PhpParser\Node\\$name";
                }
            }

            public function enterNode($node)
            {
                if (in_array(get_class($node), $this->_targetClassNames, true)) {
                    $this->targetNodes[] = $node;

                    return PhpNodeTraverser::DONT_TRAVERSE_CHILDREN;
                }

                return null;
            }
        };
    }
}
