<?php

use PHPUnit\Framework\TestCase;
use Phinder\Pattern\Parser;
use Phinder\Pattern\Node;

class PatternParseTest extends TestCase
{
    private static $_PATTERNS = [
        '_' => ['Identifier', '_', null, false],

        '\_' => ['Identifier', '_', null, true],

        '?' => ['Identifier', '?', null, false],

        '((_))' => ['Identifier', '_', null, false],

        '!_' => ['Negation', ['Identifier', '_', null, false]],

        'a' => ['Identifier', 'a', null, false],

        '_ &&& _' => [
            'Conjunction',
            ['Identifier', '_', null, false],
            ['Identifier', '_', null, false],
        ],

        '_ ||| _' => [
            'Disjunction',
            ['Identifier', '_', null, false],
            ['Identifier', '_', null, false],
        ],

        'a()' => [
            'FunctionCall',
            ['Identifier', 'a', null, false],
            [],
        ],

        'a(_, _)' => [
            'FunctionCall',
            ['Identifier', 'a', null, false],
            [
                ['Argument', ['Identifier', '_', null, false]],
                ['Argument', ['Identifier', '_', null, false]],
            ],
        ],

        'a(...)' => [
            'FunctionCall',
            ['Identifier', 'a', null, false],
            [Node::ELLIPSIS],
        ],

        'array_merge(...)' => [
            'FunctionCall',
            ['Identifier', 'array_merge', null, false],
            [Node::ELLIPSIS],
        ],

        'in_array(...)' => [
            'FunctionCall',
            ['Identifier', 'in_array', null, false],
            [Node::ELLIPSIS],
        ],

        '_->a()' => [
            'MethodCall',
            ['Identifier', '_', null, false],
            ['Identifier', 'a', null, false],
            [],
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
                ['ArrayArgument', null, ['Identifier', '_', null, false], false],
                ['ArrayArgument', null, ['Identifier', '_', null, false], false],
            ],
        ],

        '[_ => _]' => [
            'ArrayCall',
            [
                [
                    'ArrayArgument',
                    ['Identifier', '_', null, false],
                    ['Identifier', '_', null, false],
                    false,
                ],
            ],
        ],

        '[!(_ => _)]' => [
            'ArrayCall',
            [
                [
                    'ArrayArgument',
                    ['Identifier', '_', null, false],
                    ['Identifier', '_', null, false],
                    true,
                ],
            ],
        ],

        '_ . _' => [
            'StringConcatenation',
            ['Identifier', '_', null, false],
            ['Identifier', '_', null, false],
        ],

        '$this->Html->image(...)' => [
            'MethodCall',
            [
                'PropertyAccess',
                ['Variable', 'this'],
                ['Identifier', 'Html', null, false],
            ],
            ['Identifier', 'image', null, false],
            [Node::ELLIPSIS],
        ],

        'Response::forge(...)' => [
            'StaticMethodCall',
            ['Identifier', 'Response', null, false],
            ['Identifier', 'forge', null, false],
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
