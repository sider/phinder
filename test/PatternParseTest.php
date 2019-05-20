<?php

use PHPUnit\Framework\TestCase;
use Phinder\Pattern\Parser;
use Phinder\Pattern\Node;

class PatternParseTest extends TestCase
{
    private static $_PATTERNS = [
        '_' => ['Identifier', false, ['_']],

        '\_' => ['Identifier', true, ['_']],

        '?' => ['Identifier', false, ['?']],

        '((_))' => ['Identifier', false, ['_']],

        '!_' => ['Negation', ['Identifier', false, ['_']]],

        'a' => ['Identifier', false, ['a']],

        '_ &&& _' => [
            'Conjunction',
            ['Identifier', false, ['_']],
            ['Identifier', false, ['_']],
        ],

        '_ ||| _' => [
            'Disjunction',
            ['Identifier', false, ['_']],
            ['Identifier', false, ['_']],
        ],

        'a()' => [
            'FunctionCall',
            ['Identifier', false, ['a']],
            [],
        ],

        'a(_, _)' => [
            'FunctionCall',
            ['Identifier', false, ['a']],
            [
                ['Argument', ['Identifier', false, ['_']]],
                ['Argument', ['Identifier', false, ['_']]],
            ],
        ],

        'a(...)' => [
            'FunctionCall',
            ['Identifier', false, ['a']],
            [Node::ELLIPSIS],
        ],

        'array_merge(...)' => [
            'FunctionCall',
            ['Identifier', false, ['array_merge']],
            [Node::ELLIPSIS],
        ],

        'in_array(...)' => [
            'FunctionCall',
            ['Identifier', false, ['in_array']],
            [Node::ELLIPSIS],
        ],

        '_->a()' => [
            'MethodCall',
            ['Identifier', false, ['_']],
            ['Identifier', false, ['a']],
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
                ['ArrayArgument', null, ['Identifier', false, ['_']], false],
                ['ArrayArgument', null, ['Identifier', false, ['_']], false],
            ],
        ],

        '[_ => _]' => [
            'ArrayCall',
            [
                [
                    'ArrayArgument',
                    ['Identifier', false, ['_']],
                    ['Identifier', false, ['_']],
                    false,
                ],
            ],
        ],

        '[!(_ => _)]' => [
            'ArrayCall',
            [
                [
                    'ArrayArgument',
                    ['Identifier', false, ['_']],
                    ['Identifier', false, ['_']],
                    true,
                ],
            ],
        ],

        '_ . _' => [
            'StringConcatenation',
            ['Identifier', false, ['_']],
            ['Identifier', false, ['_']],
        ],

        '$this->Html->image(...)' => [
            'MethodCall',
            [
                'PropertyAccess',
                ['Variable', 'this'],
                ['Identifier', false, ['Html']],
            ],
            ['Identifier', false, ['image']],
            [Node::ELLIPSIS],
        ],

        'Response::forge(...)' => [
            'StaticMethodCall',
            ['Identifier', false, ['Response']],
            ['Identifier', false, ['forge']],
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
