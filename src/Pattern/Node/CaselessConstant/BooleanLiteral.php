<?php

namespace Phinder\Pattern\Node\CaselessConstant;

use Phinder\Pattern\Node\CaselessConstant;

final class BooleanLiteral extends CaselessConstant
{
    protected $bool;

    public function __construct($bool = null)
    {
        $this->bool = $bool;
    }

    protected function matchName($name)
    {
        if ($this->bool === null) {
            return $name === 'true' || $name === 'false';
        }

        return $name === ($this->bool ? 'true' : 'false');
    }

    protected function getSubNodeNames()
    {
        return ['bool'];
    }
}
