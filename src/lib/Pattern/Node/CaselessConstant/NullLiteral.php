<?php

namespace Phinder\Pattern\Node\CaselessConstant;

final class NullLiteral extends CaselessConstant
{
    public function __construct()
    {
        parent::__construct('null');
    }
}
