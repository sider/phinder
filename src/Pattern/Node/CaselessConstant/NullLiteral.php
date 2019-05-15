<?php

namespace Phinder\Pattern\Node\CaselessConstant;

use Phinder\Pattern\Node\CaselessConstant;

final class NullLiteral extends CaselessConstant
{
    protected function matchName($name)
    {
        return $name === 'null';
    }

    protected function getSubNodeNames()
    {
        return [];
    }
}
