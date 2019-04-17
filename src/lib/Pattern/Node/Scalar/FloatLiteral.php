<?php

namespace Phinder\Pattern\Node\Scalar;

use Phinder\Pattern\Node\Scalar;

final class FloatLiteral extends Scalar
{
    protected static function preprocess($text)
    {
        return (float) $text;
    }

    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Scalar_DNumber';
    }
}
