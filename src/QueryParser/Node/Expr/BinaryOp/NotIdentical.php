<?php declare(strict_types=1);

namespace QueryParser\Node\Expr\BinaryOp;

use QueryParser\Node\Expr\BinaryOp;

class NotIdentical extends BinaryOp
{
    public function getOperatorSigil() : string {
        return '!==';
    }
    
    public function getType() : string {
        return 'Expr_BinaryOp_NotIdentical';
    }
}
