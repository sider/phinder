<?php

namespace Phinder\Parser;

use Phinder\Error\InvalidPHP;
use PhpParser\Error;
use PhpParser\Lexer;
use PhpParser\ParserFactory;
use function Funct\Strings\endsWith;

final class PHPParser extends FileParser
{
    private $_phpParser = null;

    public function __construct()
    {
        $lexer = new Lexer(
            ['usedAttributes' => [
            'startLine',
            'endLine',
            'startTokenPos',
            'endTokenPos',
            'startFilePos',
            'endFilePos',
            ]]
        );
        $this->_phpParser = (new ParserFactory())->create(
            ParserFactory::PREFER_PHP7,
            $lexer
        );
    }

    public function parseStr($code)
    {
        try {
            $ast = $this->_phpParser->parse($code);
        } catch (Error $e) {
            throw new InvalidPHP('', $e);
        }
        $xml = new \SimpleXMLElement("<file path=''/>");
        static::_fillXML($xml, $ast);

        return $xml;
    }

    protected function support($path)
    {
        return endsWith($path, '.php') || endsWith($path, '.ctp');
    }

    protected function parseFile($path)
    {
        $code = $this->getContent($path);
        try {
            $ast = $this->_phpParser->parse($code);
        } catch (Error $e) {
            throw new InvalidPHP($path, $e);
        }
        $xml = new \SimpleXMLElement("<file path='$path'/>");
        static::_fillXML($xml, $ast);
        yield $xml;
    }

    private static function _fillXML($xml, $ast)
    {
        if (\is_array($ast)) {
            foreach ($ast as $k => $v) {
                $e = $xml->addChild("item$k");
                static::_fillXML($e, $v);
            }
        } elseif (\is_object($ast)
            && \is_subclass_of($ast, '\PhpParser\NodeAbstract')
        ) {
            $xml['startLine'] = $ast->getStartLine();
            $xml['endLine'] = $ast->getEndLine();
            $xml['startFilePosition'] = $ast->getStartFilePos();
            $xml['endFilePosition'] = $ast->getEndFilePos();
            $xml['class'] = $ast->getType();
            foreach ($ast->getSubNodeNames() as $name) {
                $e = $xml->addChild($name);
                static::_fillXML($e, $ast->$name);
            }
        } else {
            $xml[0] = \mb_convert_encoding((string) $ast, 'utf8');
        }
    }
}
