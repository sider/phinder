<?php declare(strict_types=1);

namespace Phinder\QueryParser\Node\Expr\AssignOp;

use Phinder\QueryParser\Node\Expr\AssignOp;

class Plus extends AssignOp
{
    public function getType() : string {
        return 'Expr_AssignOp_Plus';
    }
}
