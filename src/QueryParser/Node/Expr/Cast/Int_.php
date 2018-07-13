<?php declare(strict_types=1);

namespace Phinder\QueryParser\Node\Expr\Cast;

use Phinder\QueryParser\Node\Expr\Cast;

class Int_ extends Cast
{
    public function getType() : string {
        return 'Expr_Cast_Int';
    }
}
