<?php

namespace Phinder;


final class WildcardN extends \PhpParser\Node\Expr {

    public function getSubNodeNames() : array {
        return [];
    }

    public function getType() : string {
        return 'Expr_WildcardN';
    }

}
