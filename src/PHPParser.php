<?php

namespace Phinder;

use Phinder\Utility;
use PhpParser\Error;
use PhpParser\ParserFactory;

final class PHPParser {

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
            $ast = static::$parser->parse($code);
            $xml = new \SimpleXMLElement("<file path='$path'/>");
            static::buildXML($xml, $ast);
            return $xml;

        } catch (Error $error) {
            die($error->getMessage());

        }
    }

    private static function buildXML($xml, $ast) {
        if (\is_array($ast)) {
            foreach ($ast as $k => $v) {
                $e = $xml->addChild("item$k");
                static::buildXML($e, $v);
            }

        } else if (\is_subclass_of($ast, '\PhpParser\NodeAbstract')) {
            $xml['start'] = $ast->getStartLine();
            $xml['end'] = $ast->getEndLine();
            foreach ($ast->getSubNodeNames() as $name) {
                $e = $xml->addChild($name);
                static::buildXML($e, $ast->$name);
            }

        } else {
            $xml[0] = (string)$ast;

        }
    }

}
