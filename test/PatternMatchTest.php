<?php

use PHPUnit\Framework\TestCase;
use Phinder\Pattern\Parser as PatternParser;
use Phinder\PhpParser;

class PatternMatchTest extends TestCase
{
    private static $_CASES = [
        'null' => [
            true => ['echo null;'],
            false => ['echo false;'],
        ],
        'true' => [
            true => ['echo true;'],
            false => ['echo false;'],
        ],
        'false' => [
            true => ['echo false;'],
            false => ['echo true;'],
        ],
        ':bool:' => [
            true => ['echo false;', 'echo true;'],
            false => ['echo 1;'],
        ],
        '1' => [
            true => ['echo 1;'],
            false => ['echo 100;'],
        ],
        ':int:' => [
            true => ['echo 1;', 'echo 100;'],
            false => ['echo 1.0;'],
        ],
        '1.0' => [
            true => ['echo 1.0;', 'echo 1.00;'],
            false => ['echo 1;'],
        ],
        ':float:' => [
            true => ['echo 1.0;', 'echo 1.00;'],
            false => ['echo 1;'],
        ],
    ];

    private $_patternParser;

    private $_phpParser;

    public function setUp()
    {
        $this->_patternParser = new PatternParser();
        $this->_phpParser = new PhpParser();
    }

    /**
     * @dataProvider provider
     */
    public function testMatch($pattern, $php, $match)
    {
        $patAst = $this->_patternParser->parse($pattern);
        $phpAst = $this->_phpParser->parse("<?php $php");
        $this->assertSame($patAst->match($phpAst), $match);
    }

    public function provider()
    {
        $array = [];

        foreach (self::$_CASES as $pattern => $tests) {
            foreach ($tests as $match => $phps) {
                foreach ($phps as $php) {
                    $array[] = [$pattern, $php, $match ? true : false];
                }
            }
        }

        return $array;
    }
}
