<?php

namespace GrePHP;

final class Utility {
    public static function findByExt($dir_path, $ext) {
        foreach (new \RecursiveIteratorIterator (new \RecursiveDirectoryIterator ($dir_path)) as $i) {
            $p = $i->getPathname();
            if (static::endsWith($p, $ext)) yield $p;
        }
    }

    public static function endsWith($haystack, $needle) {
        return (strrpos($haystack, $needle) === strlen($haystack) - strlen($needle));
    }

}
