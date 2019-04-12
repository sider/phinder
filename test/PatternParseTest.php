<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../src/lib/PatternParser/PatternParser.php';
require_once __DIR__.'/../src/lib/PatternParser/StringReader.php';

class PatternParseTest extends TestCase
{
    private static $_GOOD_PATTERNS = [
        '_',
    ];

    private static $_BAD_PATTERNS = [
        'a',
    ];

    /**
     * @dataProvider patternProvider
     */
    public function testParse($pattern, $success)
    {
        yyinput($pattern);
        $this->assertSame(yyparse(), $success ? 0 : 1);
    }

    public function patternProvider()
    {
        $patterns = [];

        foreach (self::$_GOOD_PATTERNS as $p) {
            $patterns[] = [$p, true];
        }

        foreach (self::$_BAD_PATTERNS as $p) {
            $patterns[] = [$p, false];
        }

        return $patterns;
    }
}
