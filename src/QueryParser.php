<?php

namespace Phinder;

use Phinder\Utility;
use Phinder\QueryParser\Error;
use Phinder\QueryParser\ParserFactory;

final class QueryParser {

    protected static $parser = null;

    public static function parse($code) {
        if (!isset(static::$parser)) {
            static::$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        }

        try {
            return static::$parser->parse('<?php ' . $code);

        } catch (Error $error) {
            die($error->getMessage());

        }
    }

}
