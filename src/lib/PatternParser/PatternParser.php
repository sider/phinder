<?php

namespace Phinder\PatternParser;

final class PatternParser
{
    public static function create()
    {
        return new PatternParser();
    }

    public function parse($string)
    {
        if ($string === '_') {
            return true;
        }
        throw new PatternParseError();
    }
}
