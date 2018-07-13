<?php declare(strict_types=1);

namespace QueryParser\Node\Expr\AssignOp;

use QueryParser\Node\Expr\AssignOp;

class ShiftRight extends AssignOp
{
    public function getType() : string {
        return 'Expr_AssignOp_ShiftRight';
    }
}
