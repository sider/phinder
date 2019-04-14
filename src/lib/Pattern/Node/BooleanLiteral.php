<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class BooleanLiteral extends Node
{
    protected static $targetClassNames = ['Expr\ConstFetch'];

    private $_value;

    public function __construct($value = null)
    {
        if ($value === null) {
            $this->_value = null;
        } else {
            $this->_value = (bool) ($value === 'true');
        }
    }

    protected function matchPhpNode($phpNode)
    {
        $value = strtolower($phpNode->name->parts[0]);

        if ($value !== 'true' && $value !== 'false') {
            return false;
        }

        if ($this->_value === null) {
            return true;
        }

        if ($value === 'true' && $this->_value) {
            return true;
        }

        if ($value === 'false' && !$this->_value) {
            return true;
        }

        return false;
    }

    protected function getChildrenArray()
    {
        return [$this->_value];
    }
}
