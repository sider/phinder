<?php

use PHPUnit\Framework\TestCase;
use Phinder\Pattern\Parser;

class PatternParseTest extends TestCase
{
    private static $_GOOD_PATTERNS = [
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
    ];

    private static $_BAD_PATTERNS = [
        '?',
        '!',
        '(',
        '_ &',
        '_ |',
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
     * @dataProvider goodPatternProvider
     */
    public function testParseSuccess($pattern)
    {
        $status = $this->_parser->parse($pattern);
        $this->assertSame($status, 0);
    }

    /**
     * @dataProvider badPatternProvider
     */
    public function testParseFail($pattern)
    {
        $status = $this->_parser->parse($pattern);
        $this->assertSame($status, 1);
    }

    public function goodPatternProvider()
    {
        return self::_patternProvider(self::$_GOOD_PATTERNS);
    }

    public function badPatternProvider()
    {
        return self::_patternProvider(self::$_BAD_PATTERNS);
    }

    private static function _patternProvider($array)
    {
        $patterns = [];

        foreach ($array as $p) {
            $patterns[] = [$p];
        }

        return $patterns;
    }
}
