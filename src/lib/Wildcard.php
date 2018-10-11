<?php

namespace Phinder;


final class Wildcard extends \PhpParser\Node\Expr
{

    public function getSubNodeNames() : array
    {
        return [];
    }

    public function getType() : string
    {
        return 'Expr_Wildcard';
    }

}
