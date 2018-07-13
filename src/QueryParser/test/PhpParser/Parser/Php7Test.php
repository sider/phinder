<?php declare(strict_types=1);

namespace QueryParser\Parser;

use QueryParser\Lexer;
use QueryParser\ParserTest;

require_once __DIR__ . '/../ParserTest.php';

class Php7Test extends ParserTest
{
    protected function getParser(Lexer $lexer) {
        return new Php7($lexer);
    }
}
