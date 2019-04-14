<?php

use PHPUnit\Framework\TestCase;
use Phinder\Pattern\Parser;

class PatternParseTest extends TestCase
{
    private static $_PATTERNS = [
        '_',
        '_ | _',
        '_ & _',
        '(_ | _) & _',
        '!_',
        '((_))',
        'f',
        '_f',
        'f()',
        'f(f())',
        'f(f(), f())',
        'f(...)',
        'f(_, ..., _)',
        'true',
        'false',
        ':bool:',
    ];

    private $_parser;

    public function setUp()
    {
        $this->_parser = Parser::create();
    }

    public function tearDown()
    {
        $this->_parser = null;
    }

    /**
     * @dataProvider patternProvider
     */
    public function testParseSuccess($pattern)
    {
        $status = $this->_parser->parse($pattern);
        $this->assertSame($status, 0);
    }

    public function patternProvider()
    {
        $patterns = [];

        foreach (self::$_PATTERNS as $p) {
            $patterns[] = [$p];
        }

        return $patterns;
    }
}
