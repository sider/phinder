<?php

namespace Phinder\Pattern\Node\BinaryOperation;

use Phinder\Pattern\Node\BinaryOperation;

final class Equal extends BinaryOperation
{
    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_BinaryOp_Equal';
    }
}
