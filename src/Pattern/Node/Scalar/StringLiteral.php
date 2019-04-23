<?php

namespace Phinder\Pattern\Node\Scalar;

use Phinder\Pattern\Node\Scalar;

final class StringLiteral extends Scalar
{
    protected static function preprocess($text)
    {
        return substr($text, 1, strlen($text) - 2);
    }

    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Scalar_String';
    }
}
