<?php

namespace Phinder\Pattern\Node\BinaryOperation;

use Phinder\Pattern\Node\BinaryOperation;

final class Pow extends BinaryOperation
{
    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_BinaryOp_Pow';
    }
}
