<?php

namespace Phinder\Pattern\Node\Scalar;

class FloatLiteral extends Scalar
{
    protected static $targetTypes = [
        'Scalar_DNumber',
    ];

    protected static function preprocess($text)
    {
        return (float) $text;
    }
}
