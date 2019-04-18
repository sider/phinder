<?php

namespace Phinder\Pattern\Node\Scalar;

use Phinder\Pattern\Node\Scalar;

final class IntegerLiteral extends Scalar
{
    protected static function preprocess($text)
    {
        return (int) $text;
    }

    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Scalar_LNumber';
    }
}
