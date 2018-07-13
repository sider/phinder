<?php

namespace Phinder;

use Phinder\Utility;
use Phinder\QueryParser\Error;
use Phinder\QueryParser\ParserFactory;

final class QueryParser {

    protected static $parser = null;

    public static function parse($path) {
        if (!isset(static::$parser)) {
            static::$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        }

        if (is_dir($path)) {
            foreach (Utility::findByExt($path, 'php') as $p) {
                yield static::parseFile($p);
            }

        } else {
            yield static::parseFile($path);

        }
    }

    private static function parseFile($path) {
        $code = @file_get_contents($path) or die("Failed to get contents of $path");

        try {
            return static::$parser->parse('<?php ' . $code);

        } catch (Error $error) {
            die($error->getMessage());

        }
    }
}
