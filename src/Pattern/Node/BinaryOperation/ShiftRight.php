<?php

namespace Phinder\Pattern\Node\BinaryOperation;

use Phinder\Pattern\Node\BinaryOperation;

final class ShiftRight extends BinaryOperation
{
    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_BinaryOp_ShiftRight';
    }
}
