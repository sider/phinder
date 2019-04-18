<?php

namespace Phinder;

use Phinder\Error\FileNotFound;

final class Utility
{
    public static function fileGetContents($path)
    {
        if (($content = @file_get_contents($path)) !== false) {
            return $content;
        }
        throw new FileNotFound($path);
    }
}
