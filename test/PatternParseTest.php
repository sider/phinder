<?php

use PHPUnit\Framework\TestCase;
use Phinder\PatternParser\PatternParser;

class PatternParseTest extends TestCase
{
    private static $_GOOD_PATTERNS = [
        '_',
    ];

    private static $_BAD_PATTERNS = [
        '?',
    ];

    private $_parser;

    public function setUp()
    {
        $this->_parser = PatternParser::create();
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
        $ast = $this->_parser->parse($pattern);
        $this->assertNotSame($ast, null);
    }

    /**
     * @dataProvider      badPatternProvider
     * @expectedException \Phinder\PatternParser\PatternParseError
     */
    public function testParseFail($pattern)
    {
        $this->_parser->parse($pattern);
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
