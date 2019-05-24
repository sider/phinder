<?php

namespace Phinder\Pattern\Node\BinaryOperation;

use Phinder\Pattern\Node\BinaryOperation;

final class Smaller extends BinaryOperation
{
    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_BinaryOp_Smaller';
    }
}
