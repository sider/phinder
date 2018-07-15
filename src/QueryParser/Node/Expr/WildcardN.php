<?php declare(strict_types=1);

namespace Phinder\QueryParser\Node\Expr;

use Phinder\QueryParser\Node\Expr;

class WildcardN extends Expr
{
    public function getSubNodeNames() : array {
        return [];
    }

    public function getType() : string {
        return 'Expr_WildcardN';
    }
}
