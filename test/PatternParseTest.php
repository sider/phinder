<?php

use PHPUnit\Framework\TestCase;
use Phinder\Pattern\Parser;

class PatternParseTest extends TestCase
{
    private static $_PATTERNS = [
        '_' => ['Identifier', '_'],

        '!_' => ['Negation', ['Identifier', '_']],

        'a' => ['Identifier', 'a'],

        '_ & _' => [
            'Conjunction',
            ['Identifier', '_'],
            ['Identifier', '_'],
        ],

        '_ | _' => [
            'Disjunction',
            ['Identifier', '_'],
            ['Identifier', '_'],
        ],

        'a()' => [
            'Invocation',
            ['Identifier', 'a'],
            [],
        ],

        'a(_, _)' => [
            'Invocation',
            ['Identifier', 'a'],
            [
                ['Identifier', '_'],
                ['Identifier', '_'],
            ],
        ],

        'a(...)' => [
            'Invocation',
            ['Identifier', 'a'],
            [
                ['Ellipsis'],
            ],
        ],

        '_->a()' => [
            'MethodInvocation',
            ['Identifier', '_'],
            ['Invocation', ['Identifier', 'a'], []],
        ],

        'null' => [
            'NullLiteral',
            'null',
        ],

        'true' => [
            'BooleanLiteral',
            'true',
        ],

        'false' => [
            'BooleanLiteral',
            'false',
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
            [],
        ],

        '[]' => [
            'ArrayLiteral',
            true,
            [],
        ],

        '[_, _]' => [
            'ArrayLiteral',
            true,
            [
                ['Identifier', '_'],
                ['Identifier', '_'],
            ],
        ],

        '[_ => _]' => [
            'ArrayLiteral',
            true,
            [
                [
                    'KeyValuePair',
                    ['Identifier', '_'],
                    ['Identifier', '_'],
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
