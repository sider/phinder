<?php

use PHPUnit\Framework\TestCase;
use Phinder\Pattern\Parser as PatternParser;
use Phinder\Php\Parser as PhpParser;

class PatternMatchTest extends TestCase
{
    private static $_CASES = [
        'null' => [
            0 => ['echo false;'],
            1 => ['echo null;'],
        ],
        'true' => [
            0 => ['echo false;'],
            1 => ['echo true;'],
        ],
        'false' => [
            0 => ['echo true;'],
            1 => ['echo false;'],
        ],
        ':bool:' => [
            0 => ['echo 1;', 'echo $this;', 'echo null;', 'echo CONSTANT;'],
            1 => ['echo false;', 'echo true;'],
        ],
        '1' => [
            0 => ['echo 100;'],
            1 => ['echo 1;'],
        ],
        ':int:' => [
            0 => ['echo 1.0;'],
            1 => ['echo 1;', 'echo 100;'],
        ],
        '1.0' => [
            0 => ['echo 1;'],
            1 => ['echo 1.0;', 'echo 1.00;'],
        ],
        ':float:' => [
            0 => ['echo 1;'],
            1 => ['echo 1.0;', 'echo 1.00;'],
        ],
        '"a"' => [
            0 => ['echo $a;'],
            1 => ['echo "a";', "echo 'a';"],
        ],
        "'a'" => [
            0 => ['echo $a;'],
            1 => ['echo "a";', "echo 'a';"],
        ],
        ':string:' => [
            0 => ['echo $a;'],
            1 => ['echo "a";', "echo 'a';"],
        ],
        'f(_)' => [
            0 => ['f();', 'f(1, 2);', 'g(1);'],
            1 => ['f(1);', 'f($a);', 'f(a);'],
        ],
        'f(...)' => [
            0 => ['g();'],
            1 => ['f();', 'f(1);', 'f(1, 2);', 'f(1, 2, 3);'],
        ],
        'f(_, ...)' => [
            0 => ['f();'],
            1 => ['f(1);', 'f(1, 2);', 'f(1, 2, 3);'],
        ],
        'f(..., null, ...)' => [
            0 => ['f(1, 2);'],
            1 => ['f(null);', 'f(1, null);', 'f(1, null, 2);'],
        ],
        '[1]' => [
            0 => ['print_r(["1"]);', 'print_r([1 => 1]);'],
            1 => ['print_r([1]);'],
        ],
        '[1 => _]' => [
            0 => ['print_r([1]);'],
            1 => ['print_r([1 => 1]);'],
        ],
        '?->f()' => [
            0 => ['f();'],
            1 => ['$a->f();'],
        ],
        'f(!true)' => [
            0 => ['f(true);'],
            1 => ['f(false);', 'f(1);'],
        ],
        'f(false) ||| f(0)' => [
            0 => ['f([]);'],
            1 => ['f(false);', 'f(0);'],
        ],
        'f(..., true) &&& f(false, ...)' => [
            0 => ['f();'],
            1 => ['f(false, true);', 'f(false, 1, true);'],
        ],
        '_ . _' => [
            0 => ['X + Y;'],
            1 => ['X . Y;'],
            2 => ['X . Y . Z;'],
        ],
        '$this->Html->image(...)' => [
            0 => ['$this->image(1);'],
            1 => ['$this->Html->image(1);'],
        ],
        'Response::forge(...)' => [
            0 => ['$response->forge(1);'],
            1 => ['Response::forge(1);'],
        ],
        '\\Security::fetch_token()' => [
            0 => ['Security::fetch_token()'],
            1 => ['\\Security::fetch_token()'],
        ],
        'g\\f()' => [
            0 => ['f();'],
            1 => ['g\\f();', 'h\\g\\f();', '\\g\\f();'],
        ],
        '\\g\\f()' => [
            0 => ['g\\f();', 'h\\g\\f();'],
            1 => ['\\g\\f();'],
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
    public function testMatch($pattern, $php, $matchCount)
    {
        $patAst = $this->_patternParser->parse($pattern);
        $phpAst = $this->_phpParser->parseString("<?php $php");
        $matches = $patAst->visit($phpAst);
        $this->assertSame(count($matches), $matchCount);
    }

    public function provider()
    {
        $array = [];

        foreach (self::$_CASES as $pattern => $tests) {
            foreach ($tests as $matchCount => $phps) {
                foreach ($phps as $php) {
                    $array[] = [$pattern, $php, $matchCount];
                }
            }
        }

        return $array;
    }
}
