<?php

namespace Phinder;

use PhpParser\Lexer;
use PhpParser\ParserFactory;

final class PHPParser
{
    private $_phpParser = null;

    public function __construct()
    {
        $this->_phpParser = (new ParserFactory())->create(
            ParserFactory::PREFER_PHP7,
            self::_createLexer()
        );
    }

    public function parse($string)
    {
        return $this->_phpParser->parse($string);
    }

    private static function _createLexer()
    {
        return new Lexer(
            [
                'usedAttributes' => [
                    'startLine',
                    'endLine',
                    'startTokenPos',
                    'endTokenPos',
                    'startFilePos',
                    'endFilePos',
                ],
            ]
        );
    }
}
