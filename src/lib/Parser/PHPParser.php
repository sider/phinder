<?php

namespace Phinder\Parser;

use Phinder\Error\InvalidPHP;
use PhpParser\{Error,Lexer,ParserFactory};
use function Funct\Strings\endsWith;


final class PHPParser extends FileParser
{

    private $phpParser = null;

    public function __construct()
    {
        $lexer = new Lexer(
            ['usedAttributes' => [
            'startLine',
            'endLine',
            'startTokenPos',
            'endTokenPos',
            'startFilePos',
            'endFilePos'
            ]]
        );
        $this->phpParser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7, $lexer);
    }

    public function parseStr($code)
    {
        try {
            $ast = $this->phpParser->parse($code);
        } catch (Error $e) {
            throw new InvalidPHP($path, $e);
        }
        $xml = new \SimpleXMLElement("<file path=''/>");
        static::fillXML($xml, $ast);
        return $xml;
    }

    protected function support($path)
    {
        return endsWith($path, '.php');
    }

    protected function parseFile($path)
    {
        $code = $this->getContent($path);
        try {
            $ast = $this->phpParser->parse($code);
        } catch (Error $e) {
            throw new InvalidPHP($path, $e);
        }
        $xml = new \SimpleXMLElement("<file path='$path'/>");
        static::fillXML($xml, $ast);
        yield $xml;
    }

    private static function fillXML($xml, $ast)
    {
        if (\is_array($ast)) {
            foreach ($ast as $k => $v) {
                $e = $xml->addChild("item$k");
                static::fillXML($e, $v);
            }

        } else if (\is_subclass_of($ast, '\PhpParser\NodeAbstract')) {
            $xml['startLine'] = $ast->getStartLine();
            $xml['endLine'] = $ast->getEndLine();
            $xml['startFilePosition'] = $ast->getStartFilePos();
            $xml['endFilePosition'] = $ast->getEndFilePos();
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
