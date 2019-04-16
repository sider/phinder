<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class BooleanLiteral extends Node
{
    protected static $targetTypes = [
        'Expr_ConstFetch',
    ];

    private $_value;

    public function __construct($value = null)
    {
        if ($value === null) {
            $this->_value = null;
        } else {
            $this->_value = (bool) ($value === 'true');
        }
    }

    protected function match($phpNode)
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

    public function toArray()
    {
        return [
            $this->getShortName(),
            $this->_value,
        ];
    }
}
