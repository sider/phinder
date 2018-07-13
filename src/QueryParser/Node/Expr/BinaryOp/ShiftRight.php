<?php declare(strict_types=1);

namespace QueryParser\Node\Expr\BinaryOp;

use QueryParser\Node\Expr\BinaryOp;

class ShiftRight extends BinaryOp
{
    public function getOperatorSigil() : string {
        return '>>';
    }
    
    public function getType() : string {
        return 'Expr_BinaryOp_ShiftRight';
    }
}
