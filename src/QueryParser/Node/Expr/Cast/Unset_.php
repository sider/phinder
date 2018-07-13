<?php declare(strict_types=1);

namespace QueryParser\Node\Expr\Cast;

use QueryParser\Node\Expr\Cast;

class Unset_ extends Cast
{
    public function getType() : string {
        return 'Expr_Cast_Unset';
    }
}
