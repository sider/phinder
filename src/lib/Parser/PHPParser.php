<?php

namespace Phinder\Parser;

use PhpParser\{Error,ParserFactory};
use function Funct\Strings\endsWith;


final class PHPParser extends FileParser {

    private $phpParser = null;

    public function __construct() {
        $this->phpParser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
    }

    protected function support($path) {
        return endsWith($path, '.php');
    }

    protected function parseFile($path) {
        $code = $this->getContent($path);
        $ast = $this->phpParser->parse($code);
        $xml = new \SimpleXMLElement("<file path='$path'/>");
        static::fillXML($xml, $ast);
        yield $xml;
    }

    private static function fillXML($xml, $ast) {
        if (\is_array($ast)) {
            foreach ($ast as $k => $v) {
                $e = $xml->addChild("item$k");
                static::fillXML($e, $v);
            }

        } else if (\is_subclass_of($ast, '\PhpParser\NodeAbstract')) {
            $xml['start'] = $ast->getStartLine();
            $xml['class'] = $ast->getType();
            foreach ($ast->getSubNodeNames() as $name) {
                $e = $xml->addChild($name);
                static::fillXML($e, $ast->$name);
            }

        } else {
            $xml[0] = \mb_convert_encoding((string)$ast, 'utf8');

        }
    }

}
