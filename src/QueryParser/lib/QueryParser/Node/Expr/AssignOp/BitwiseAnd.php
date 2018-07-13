<?php declare(strict_types=1);

namespace QueryParser\Node\Expr\AssignOp;

use QueryParser\Node\Expr\AssignOp;

class BitwiseAnd extends AssignOp
{
    public function getType() : string {
        return 'Expr_AssignOp_BitwiseAnd';
    }
}
