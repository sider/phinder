<?php

use PHPUnit\Framework\TestCase;
use Phinder\Pattern\Parser;
use Phinder\Pattern\Node;

class PatternParseTest extends TestCase
{
    private static $_PATTERNS = [
        '_' => ['Identifier', '_'],

        '?' => ['Identifier', '?'],

        '((_))' => ['Identifier', '_'],

        '!_' => ['Negation', ['Identifier', '_']],

        'a' => ['Identifier', 'a'],

        '_ &&& _' => [
            'Conjunction',
            ['Identifier', '_'],
            ['Identifier', '_'],
        ],

        '_ ||| _' => [
            'Disjunction',
            ['Identifier', '_'],
            ['Identifier', '_'],
        ],

        'a()' => [
            'FunctionCall',
            ['Identifier', 'a'],
            [],
        ],

        'a(_, _)' => [
            'FunctionCall',
            ['Identifier', 'a'],
            [
                ['Argument', ['Identifier', '_']],
                ['Argument', ['Identifier', '_']],
            ],
        ],

        'a(...)' => [
            'FunctionCall',
            ['Identifier', 'a'],
            [Node::ELLIPSIS],
        ],

        'array_merge(...)' => [
            'FunctionCall',
            ['Identifier', 'array_merge'],
            [Node::ELLIPSIS],
        ],

        'in_array(...)' => [
            'FunctionCall',
            ['Identifier', 'in_array'],
            [Node::ELLIPSIS],
        ],

        '_->a()' => [
            'MethodCall',
            ['Identifier', '_'],
            ['Identifier', 'a'],
            [],
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
            'ArrayCall',
            [],
        ],

        '[]' => [
            'ArrayCall',
            [],
        ],

        '[_, _]' => [
            'ArrayCall',
            [
                ['ArrayArgument', null, ['Identifier', '_'], false],
                ['ArrayArgument', null, ['Identifier', '_'], false],
            ],
        ],

        '[_ => _]' => [
            'ArrayCall',
            [
                [
                    'ArrayArgument',
                    ['Identifier', '_'],
                    ['Identifier', '_'],
                    false,
                ],
            ],
        ],

        '[!(_ => _)]' => [
            'ArrayCall',
            [
                [
                    'ArrayArgument',
                    ['Identifier', '_'],
                    ['Identifier', '_'],
                    true,
                ],
            ],
        ],

        '_ . _' => [
            'StringConcatenation',
            ['Identifier', '_'],
            ['Identifier', '_'],
        ],

        '$this->Html->image(...)' => [
            'MethodCall',
            [
                'PropertyAccess',
                ['Variable', 'this'],
                ['Identifier', 'Html'],
            ],
            ['Identifier', 'image'],
            [Node::ELLIPSIS],
        ],

        'Response::forge(...)' => [
            'StaticMethodCall',
            ['Identifier', 'Response'],
            ['Identifier', 'forge'],
            [Node::ELLIPSIS],
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
