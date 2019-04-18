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
        '"a"' => [
            true => ['echo "a";', "echo 'a';"],
            false => ['echo $a;'],
        ],
        "'a'" => [
            true => ['echo "a";', "echo 'a';"],
            false => ['echo $a;'],
        ],
        ':string:' => [
            true => ['echo "a";', "echo 'a';"],
            false => ['echo $a;'],
        ],
        'f(_)' => [
            true => ['f(1);', 'f($a);', 'f(a);'],
            false => ['f();', 'f(1, 2);', 'g(1);'],
        ],
        'f(...)' => [
            true => ['f();', 'f(1);', 'f(1, 2);', 'f(1, 2, 3);'],
            false => ['g();'],
        ],
        'f(_, ...)' => [
            true => ['f(1);', 'f(1, 2);', 'f(1, 2, 3);'],
            false => ['f();'],
        ],
        'f(..., null, ...)' => [
            true => ['f(null);', 'f(1, null);', 'f(1, null, 2);'],
            false => ['f(1, 2);'],
        ],
        '[1]' => [
            true => ['print_r([1]);'],
            false => ['print_r(["1"]);', 'print_r([1 => 1]);'],
        ],
        '[1 => _]' => [
            true => ['print_r([1 => 1]);'],
            false => ['print_r([1]);'],
        ],
        '_->f()' => [
            true => ['$a->f();'],
            false => ['f();'],
        ],
        'f(!true)' => [
            true => ['f(false);', 'f(1);'],
            false => ['f(true);'],
        ],
        'f(false) ||| f(0)' => [
            true => ['f(false);', 'f(0);'],
            false => ['f([]);'],
        ],
        'f(..., true) &&& f(false, ...)' => [
            true => ['f(false, true);', 'f(false, 1, true);'],
            false => ['f();'],
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
        $phpAst = $this->_phpParser->parseString("<?php $php");
        $matches = $patAst->visit($phpAst);
        if ($match) {
            $this->assertNotSame(count($matches), 0);
        } else {
            $this->assertSame(count($matches), 0);
        }
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
