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
            $ast = static::$parser->parse('<?php ' . $code);
            return '/' .  static::buildXPath($ast);

        } catch (Error $error) {
            die($error->getMessage());

        }
    }

    private static function buildXPath($ast) {
        if (\is_array($ast)) {
            return '';
        } else if (\is_subclass_of($ast, '\Phinder\QueryParser\Expr\Wildcard')) {
            return "/*";
        } else if (\is_subclass_of($ast, '\Phinder\QueryParser\Expr\WildcardN')) {
            return "/*";
        } else if (\is_subclass_of($ast, '\Phinder\QueryParser\NodeAbstract')) {
            $t = $ast->getType();
            return "/*[@class='$t']";
        } else {
            return '';
        }
    }

}
