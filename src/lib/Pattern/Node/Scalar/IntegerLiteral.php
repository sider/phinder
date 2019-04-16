<?php

namespace Phinder\Pattern\Node\Scalar;

class IntegerLiteral extends Scalar
{
    protected static $targetTypes = [
        'Scalar_LNumber',
    ];

    protected static function preprocess($text)
    {
        return (int) $text;
    }
}
