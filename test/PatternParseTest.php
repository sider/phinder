<?php

use PHPUnit\Framework\TestCase;
use Phinder\Pattern\Parser;

class PatternParseTest extends TestCase
{
    private static $_PATTERNS = [
        '_' => ['Wildcard', false],

        '!_' => ['Not', ['Wildcard', false]],

        'a' => ['Identifier', 'a'],

        '_ & _' => [
            'Conjunction',
            ['Wildcard', false],
            ['Wildcard', false],
        ],

        '_ | _' => [
            'Disjunction',
            ['Wildcard', false],
            ['Wildcard', false],
        ],

        'a()' => [
            'Invocation',
            'a',
            ['Arguments'],
        ],

        'a(_, _)' => [
            'Invocation',
            'a',
            [
                'Arguments',
                ['Wildcard', false],
                ['Arguments', ['Wildcard', false]],
            ],
        ],

        '_->a()' => [
            'MethodInvocation',
            ['Wildcard', false],
            ['Invocation', 'a', ['Arguments']],
        ],

        'null' => [
            'NullLiteral',
        ],

        'true' => [
            'BooleanLiteral',
            true,
        ],

        'false' => [
            'BooleanLiteral',
            false,
        ],

        ':bool:' => [
            'BooleanLiteral',
            null,
        ],

        '1' => [
            'IntegerLiteral',
            1,
        ],

        ':int:' => [
            'IntegerLiteral',
            null,
        ],

        '1.0' => [
            'FloatLiteral',
            1.0,
        ],

        ':float:' => [
            'FloatLiteral',
            null,
        ],

        '"a"' => [
            'StringLiteral',
            'a',
        ],

        "'a'" => [
            'StringLiteral',
            'a',
        ],

        ':string:' => [
            'StringLiteral',
            null,
        ],

        'array()' => [
            'ArrayLiteral',
            false,
            ['ArrayElements'],
        ],

        '[]' => [
            'ArrayLiteral',
            true,
            ['ArrayElements'],
        ],

        '[_, _]' => [
            'ArrayLiteral',
            true,
            [
                'ArrayElements',
                ['Wildcard', false],
                ['ArrayElements', ['Wildcard', false]],
            ],
        ],

        '[_ => _]' => [
            'ArrayLiteral',
            true,
            [
                'ArrayElements',
                [
                    'KeyValuePair',
                    ['Wildcard', false],
                    ['Wildcard', false],
                ],
            ],
        ],
    ];

    private $_parser;

    public function setUp()
    {
        $this->_parser = new Parser();
    }

    /**
     * @dataProvider patternProvider
     */
    public function testParse($pattern, $array)
    {
        $ast = $this->_parser->parse($pattern);
        $this->assertNotSame($ast, null);
        $this->assertSame($ast->toArray(), $array);
    }

    public function patternProvider()
    {
        $patterns = [];

        foreach (self::$_PATTERNS as $p => $a) {
            $patterns[] = [$p, $a];
        }

        return $patterns;
    }
}
