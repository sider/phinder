<?php

namespace Phinder\Pattern\Node\Scalar;

class StringLiteral extends Scalar
{
    protected static $targetTypes = [
        'Scalar_String',
    ];

    protected static function preprocess($text)
    {
        return substr($text, 1, strlen($text) - 2);
    }
}
