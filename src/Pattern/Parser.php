<?php

namespace Phinder\Pattern;

use Phinder\Error\InvalidPattern;
use Phinder\Pattern\Node\BinaryOperation\BitwiseAnd;
use Phinder\Pattern\Node\BinaryOperation\BitwiseOr;
use Phinder\Pattern\Node\BinaryOperation\BitwiseXor;
use Phinder\Pattern\Node\BinaryOperation\BooleanAnd;
use Phinder\Pattern\Node\BinaryOperation\BooleanOr;
use Phinder\Pattern\Node\BinaryOperation\Coalesce;
use Phinder\Pattern\Node\BinaryOperation\Concat;
use Phinder\Pattern\Node\BinaryOperation\Div;
use Phinder\Pattern\Node\BinaryOperation\Equal;
use Phinder\Pattern\Node\BinaryOperation\Greater;
use Phinder\Pattern\Node\BinaryOperation\GreaterOrEqual;
use Phinder\Pattern\Node\BinaryOperation\Identical;
use Phinder\Pattern\Node\BinaryOperation\LogicalAnd;
use Phinder\Pattern\Node\BinaryOperation\LogicalOr;
use Phinder\Pattern\Node\BinaryOperation\LogicalXor;
use Phinder\Pattern\Node\BinaryOperation\Minus;
use Phinder\Pattern\Node\BinaryOperation\Mod;
use Phinder\Pattern\Node\BinaryOperation\Mul;
use Phinder\Pattern\Node\BinaryOperation\NotEqual;
use Phinder\Pattern\Node\BinaryOperation\NotIdentical;
use Phinder\Pattern\Node\BinaryOperation\Plus;
use Phinder\Pattern\Node\BinaryOperation\Pow;
use Phinder\Pattern\Node\BinaryOperation\ShiftLeft;
use Phinder\Pattern\Node\BinaryOperation\ShiftRight;
use Phinder\Pattern\Node\BinaryOperation\Smaller;
use Phinder\Pattern\Node\BinaryOperation\SmallerOrEqual;
use Phinder\Pattern\Node\BinaryOperation\Spaceship;
use Phinder\Pattern\Node\Call\ArrayCall;
use Phinder\Pattern\Node\Call\FunctionCall;
use Phinder\Pattern\Node\Call\MethodCall;
use Phinder\Pattern\Node\Call\StaticMethodCall;
use Phinder\Pattern\Node\CaselessConstant\BooleanLiteral;
use Phinder\Pattern\Node\CaselessConstant\NullLiteral;
use Phinder\Pattern\Node\LogicalOperation\BinaryOperation\Conjunction;
use Phinder\Pattern\Node\LogicalOperation\BinaryOperation\Disjunction;
use Phinder\Pattern\Node\LogicalOperation\Negation;
use Phinder\Pattern\Node\Scalar\FloatLiteral;
use Phinder\Pattern\Node\Scalar\IntegerLiteral;
use Phinder\Pattern\Node\Scalar\StringLiteral;
use Phinder\Pattern\Node\Argument;
use Phinder\Pattern\Node\ArrayArgument;
use Phinder\Pattern\Node\Identifier;
use Phinder\Pattern\Node\PropertyAccess;
use Phinder\Pattern\Node\Variable;
use Phinder\Pattern\Node;

class Parser
{
    const YYERRTOK = 256;

    const T_VARIABLE = 257;

    const T_COMMA = 258;

    const T_ARROW = 259;

    const T_ARRAY = 260;

    const T_SPACESHIP = 261;

    const T_DOUBLE_ARROW_RIGHT = 262;

    const T_DOUBLE_ARROW_LEFT = 263;

    const T_ELLIPSIS = 264;

    const T_DOT = 265;

    const T_TRIPLE_VERTICAL_BAR = 266;

    const T_DOUBLE_VERTICAL_BAR = 267;

    const T_VERTICAL_BAR = 268;

    const T_TRIPLE_AMPERSAND = 269;

    const T_DOUBLE_AMPERSAND = 270;

    const T_AMPERSAND = 271;

    const T_EXCLAMATION_DOUBLE_EQUAL = 272;

    const T_EXCLAMATION_EQUAL = 273;

    const T_EXCLAMATION = 274;

    const T_LEFT_PAREN = 275;

    const T_RIGHT_PAREN = 276;

    const T_LEFT_BRACKET = 277;

    const T_RIGHT_BRACKET = 278;

    const T_NULL = 279;

    const T_BOOLEAN = 280;

    const T_INTEGER = 281;

    const T_FLOAT = 282;

    const T_STRING = 283;

    const T_BOOLEAN_LITERAL = 284;

    const T_FLOAT_LITERAL = 285;

    const T_INTEGER_LITERAL = 286;

    const T_STRING_LITERAL = 287;

    const T_AND = 288;

    const T_OR = 289;

    const T_XOR = 290;

    const T_DOUBLE_QUESTION = 291;

    const T_IDENTIFIER = 292;

    const T_DOUBLE_COLON = 293;

    const T_BACKSLASH = 294;

    const T_CARET = 295;

    const T_SLASH = 296;

    const T_MINUS = 297;

    const T_PLUS = 298;

    const T_PERCENT = 299;

    const T_DOUBLE_ASTERISK = 300;

    const T_ASTERISK = 301;

    const T_TRIPLE_EQUAL = 302;

    const T_DOUBLE_EQUAL = 303;

    const T_DOUBLE_RIGHT_TBRACKET = 304;

    const T_RIGHT_TBRACKET_EQUAL = 305;

    const T_RIGHT_TBRACKET = 306;

    const T_DOUBLE_LEFT_TBRACKET = 307;

    const T_LEFT_TBRACKET = 308;

    const YYBADCH = 54;

    const YYMAXLEX = 309;

    const YYLAST = 110;

    const YY2TBLSTATE = 83;

    const YYNLSTATES = 100;

    const YYINTERRTOK = 1;

    const YYUNEXPECTED = 32767;

    const YYDEFAULT = -32766;

    const YYGLAST = 80;

    private $_yylval = null;

    private $_lexer = null;

    private $_yyastk = null;

    private $_yytranslate = [
            0,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,   54,   54,   54,   54,
           54,   54,   54,   54,   54,   54,    1,    2,    3,    4,
            5,    6,    7,    8,    9,   10,   11,   12,   13,   14,
           15,   16,   17,   18,   19,   20,   21,   22,   23,   24,
           25,   26,   27,   28,   29,   30,   31,   32,   33,   34,
           35,   36,   37,   38,   39,   40,   41,   42,   43,   44,
           45,   46,   47,   48,   49,   50,   51,   52,   53
    ];

    private $_yyaction = [
           81,   78,   47,    0,   48,   42,   49,  139,   50,   51,
           43,   52,   53,   54,   55,  109,   77,   46,   75,  139,
          149,   38,   74,   86,   84,   39,   40,   83,   41,   56,
           57,   58,   59,   76,   82,  129,   60,   61,   62,   63,
           64,   65,   66,   67,   68,   69,   70,   71,   72,   73,
          128,  130,  131,   85,  109,  158,    0,    0,  150,    0,
            0,    0,    0,    0,    0,    0,    0,   44,   45,    0,
           37,    0,  140,  142,  144,  146,  148,  141,  145,  143,
          147,   -5,    0,    0,   -5,   86,   -7,   83,   -5,   -7,
            0,    0,    0,   -7,    0,   -5,    0,    0,    0,    0,
            0,    0,   -5,    0,   -5,    0,    0,   -7,    0,   -7
    ];

    private $_yycheck = [
            4,    7,    6,    0,    8,    3,   10,    9,   12,   13,
            3,   15,   16,   17,   18,   21,    7,   19,   14,    9,
           21,   20,   11,   37,   39,   20,   20,   39,   20,   33,
           34,   35,   36,   20,   38,   21,   40,   41,   42,   43,
           44,   45,   46,   47,   48,   49,   50,   51,   52,   53,
            2,   21,   21,    5,   21,   21,   -1,   -1,   23,   -1,
           -1,   -1,   -1,   -1,   -1,   -1,   -1,   19,   20,   -1,
           22,   -1,   24,   25,   26,   27,   28,   29,   30,   31,
           32,    0,   -1,   -1,    3,   37,    0,   39,    7,    3,
           -1,   -1,   -1,    7,   -1,   14,   -1,   -1,   -1,   -1,
           -1,   -1,   21,   -1,   23,   -1,   -1,   21,   -1,   23
    ];

    private $_yybase = [
           48,   -6,   33,    9,   34,   -4,   -4,   -4,   -4,   -4,
           -4,   -4,   -4,   -4,   -4,   -4,   -4,   -4,   -4,   -4,
           -4,   -4,   -4,   -4,   -4,   -4,   -4,   -4,   -4,   -4,
           -4,   -4,   -4,   -4,   -4,   -4,   -4,   -2,   -2,   10,
           10,   10,   -2,   10,   48,   48,   13,   48,   48,   48,
           48,   48,   48,   48,   48,   48,   48,   48,   48,   48,
           48,   48,   48,   48,   48,   48,   48,   48,   48,   48,
           48,   48,   48,   48,   48,   48,   48,   48,   48,   81,
           86,  -12,  -12,  -14,  -14,    1,  -15,    3,   11,    4,
            5,   35,    2,   -1,    6,    8,   14,    7,   30,   31,
            0,   -4,   -4,   -4,   -4,    0,    0,    0,    0,    0,
            0,    0,    0,    0,    0,    0,    0,    0,    0,    0,
            0,    0,    0,    0,    0,    0,    0,    0,    0,    0,
            0,    0,    0,    0,    0,    0,    0,   48,   48,   48,
           48,   48,   48,   48,    0,    0,   48,    0,    0,    0,
            0,    0,    0,    0,    0,    0,    0,    0,    0,    0,
            0,    0,    0,    0,    0,    0,    0,    0,    0,    0,
            0,    0,    0,    0,    0,    0,    0,    0,    0,   11,
            0,  -14,  -14
    ];

    private $_yydefault = [
        32767,32767,32767,   55,32767,    1,32767,   85,   84,   65,
           63,   60,   62,   59,   78,   77,   71,   72,   73,   64,
           61,   66,   74,   79,   75,   80,   76,   70,   67,   82,
           69,   68,   81,   83,    3,   37,   57,   51,   51,   33,
           33,   33,32767,32767,32767,32767,32767,32767,32767,32767,
        32767,32767,32767,32767,32767,32767,32767,32767,32767,32767,
        32767,32767,32767,32767,32767,32767,32767,32767,32767,32767,
        32767,32767,32767,32767,32767,32767,32767,32767,32767,    2,
            6,32767,32767,32767,32767,32767,   25,32767,    2,    4,
           10,32767,   53,32767,   32,32767,32767,   35,32767,32767
    ];

    private $_yygoto = [
           93,    5,  156,  156,   80,  154,   80,  156,   98,   99,
           94,   95,  136,  127,  126,   79,    0,    0,    0,    0,
            0,    0,    0,    0,    0,    0,    0,    0,    0,    0,
            0,    0,    0,    0,    0,    0,    0,    0,    3,    3,
            0,    0,    0,    3,    0,    6,    2,    6,    7,    8,
            9,   10,   11,   12,   13,   14,   15,   16,   17,   18,
           19,   20,   21,   22,   23,   24,   25,   26,   27,   28,
           29,   30,   31,   32,   33,   34,    6,    1,   36,    4
    ];

    private $_yygcheck = [
           26,    2,   25,   25,    5,   27,    5,   25,   22,   22,
            7,    7,   23,   20,   20,    3,   -1,   -1,   -1,   -1,
           -1,   -1,   -1,   -1,   -1,   -1,   -1,   -1,   -1,   -1,
           -1,   -1,   -1,   -1,   -1,   -1,   -1,   -1,    2,    2,
           -1,   -1,   -1,    2,   -1,    2,    2,    2,    2,    2,
            2,    2,    2,    2,    2,    2,    2,    2,    2,    2,
            2,    2,    2,    2,    2,    2,    2,    2,    2,    2,
            2,    2,    2,    2,    2,    2,    2,    2,    2,    2
    ];

    private $_yygbase = [
            0,    0,    1,  -60,    0,  -40,    0,  -71,    0,    0,
            0,    0,    0,    0,    0,    0,    0,    0,    0,    0,
          -70,    0,  -32,  -31,    0,  -35,  -38,  -37,    0
    ];

    private $_yygdefault = [
        -32768,   87,   35,   88,   89,  106,  108,   90,  111,  112,
          113,  114,  115,  116,  117,  118,  119,  120,  121,  122,
          123,  124,   96,  134,   97,  138,   91,  152,   92
    ];

    private $_yylhs = [
            0,    1,    2,    2,    3,    3,    4,    4,    5,    5,
            6,    6,    6,    6,    6,    6,    6,    6,    6,    6,
            6,    6,    6,    7,    7,   20,   20,   21,    8,    9,
           10,   11,   12,   22,   22,   23,   23,   24,   24,   25,
           14,   15,   15,   16,   16,   17,   17,   18,   18,   13,
           13,   26,   26,   27,   27,   28,   28,   28,   28,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19
    ];

    private $_yylen = [
            1,    1,    1,    3,    1,    3,    1,    2,    1,    3,
            1,    1,    1,    1,    1,    1,    1,    1,    1,    1,
            1,    1,    1,    1,    1,    1,    3,    2,    1,    4,
            6,    6,    3,    0,    1,    1,    3,    1,    1,    1,
            1,    1,    1,    1,    1,    1,    1,    1,    1,    4,
            3,    0,    1,    1,    3,    1,    1,    3,    6,    3,
            3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
            3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
            3,    3,    3,    3,    3,    3
    ];

    public function parse($string)
    {
        $this->_lexer = new Lexer($string);
        $status = $this->_yyparse();

        if ($status === 0) {
            return $this->_yyastk[1];
        }

        throw new InvalidPattern($string);
    }

    private function _yyparse()
    {
        $this->_yyastk = [];
        $yysstk = [];
        $yyn = 0;
        $yyl = 0;
        $yystate = 0;
        $yychar = -1;
        $yysp = 0;
        $yysstk[$yysp] = 0;
        $yyerrflag = 0;

        while (true) {
            if ($this->_yybase[$yystate] == 0) {
                $yyn = $this->_yydefault[$yystate];
            } else {
                if ($yychar < 0) {
                    if (($yychar = $this->_yylex()) <= 0) {
                        $yychar = 0;
                    }
                    $yychar = $yychar < self::YYMAXLEX ? $this->_yytranslate[$yychar] : self::YYBADCH;
                }

                if ((($yyn = $this->_yybase[$yystate] + $yychar) >= 0
                    && $yyn < self::YYLAST
                    && $this->_yycheck[$yyn] == $yychar
                    || ($yystate < self::YY2TBLSTATE
                    && ($yyn = $this->_yybase[$yystate + self::YYNLSTATES] + $yychar) >= 0
                    && $yyn < self::YYLAST
                    && $this->_yycheck[$yyn] == $yychar))
                    && ($yyn = $this->_yyaction[$yyn]) != self::YYDEFAULT
                ) {
                    if ($yyn > 0) {
                        ++$yysp;
                        $yysstk[$yysp] = $yystate = $yyn;
                        $this->_yyastk[$yysp] = $this->_yylval;
                        $yychar = -1;

                        if ($yyerrflag > 0) {
                            --$yyerrflag;
                        }
                        if ($yyn < self::YYNLSTATES) {
                            continue;
                        }

                        $yyn -= self::YYNLSTATES;
                    } else {
                        $yyn = -$yyn;
                    }
                } else {
                    $yyn = $this->_yydefault[$yystate];
                }
            }

            while (true) {
                if ($yyn == 0) {
                    $this->_yyflush();

                    return 0;
                } elseif ($yyn != self::YYUNEXPECTED) {
                    $yyl = $this->_yylen[$yyn];
                    $n = $yysp - $yyl + 1;
                    $yyval = isset($this->_yyastk[$n]) ? $this->_yyastk[$n] : null;
                    switch ($yyn) {
                    case 1:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 2:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 3:
                         $yyval = new Disjunction($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 4:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 5:
                         $yyval = new Conjunction($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 6:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 7:
                         $yyval = new Negation($this->_yyastk[$yysp - (2 - 2)]); 
                        break;
                    case 8:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 9:
                         $yyval = $this->_yyastk[$yysp - (3 - 2)]; 
                        break;
                    case 10:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 11:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 12:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 13:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 14:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 15:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 16:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 17:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 18:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 19:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 20:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 21:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 22:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 23:
                         $yyval = new Identifier(false, $this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 24:
                         $yyval = new Identifier(true, $this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 25:
                         $yyval = [$this->_yyastk[$yysp - (1 - 1)]]; 
                        break;
                    case 26:
                         $yyval = array_merge([$this->_yyastk[$yysp - (3 - 1)]], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 27:
                         $yyval = $this->_yyastk[$yysp - (2 - 2)]; 
                        break;
                    case 28:
                         $yyval = new Variable($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 29:
                         $yyval = new FunctionCall($this->_yyastk[$yysp - (4 - 1)], $this->_yyastk[$yysp - (4 - 3)]); 
                        break;
                    case 30:
                         $yyval = new MethodCall($this->_yyastk[$yysp - (6 - 1)], $this->_yyastk[$yysp - (6 - 3)], $this->_yyastk[$yysp - (6 - 5)]); 
                        break;
                    case 31:
                         $yyval = new StaticMethodCall($this->_yyastk[$yysp - (6 - 1)], $this->_yyastk[$yysp - (6 - 3)], $this->_yyastk[$yysp - (6 - 5)]); 
                        break;
                    case 32:
                         $yyval = new PropertyAccess($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 33:
                         $yyval = []; 
                        break;
                    case 34:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 35:
                         $yyval = [$this->_yyastk[$yysp - (1 - 1)]]; 
                        break;
                    case 36:
                         $yyval = array_merge([$this->_yyastk[$yysp - (3 - 1)]], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 37:
                         $yyval = new Argument($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 38:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 39:
                         $yyval = Node::ELLIPSIS; 
                        break;
                    case 40:
                         $yyval = new NullLiteral(); 
                        break;
                    case 41:
                         $yyval = new BooleanLiteral($this->_yyastk[$yysp - (1 - 1)] === 'true'); 
                        break;
                    case 42:
                         $yyval = new BooleanLiteral(); 
                        break;
                    case 43:
                         $yyval = new IntegerLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 44:
                         $yyval = new IntegerLiteral(); 
                        break;
                    case 45:
                         $yyval = new FloatLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 46:
                         $yyval = new FloatLiteral(); 
                        break;
                    case 47:
                         $yyval = new StringLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 48:
                         $yyval = new StringLiteral(); 
                        break;
                    case 49:
                         $yyval = new ArrayCall($this->_yyastk[$yysp - (4 - 3)]); 
                        break;
                    case 50:
                         $yyval = new ArrayCall($this->_yyastk[$yysp - (3 - 2)]); 
                        break;
                    case 51:
                         $yyval = []; 
                        break;
                    case 52:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 53:
                         $yyval = [$this->_yyastk[$yysp - (1 - 1)]]; 
                        break;
                    case 54:
                         $yyval = array_merge([$this->_yyastk[$yysp - (3 - 1)]], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 55:
                         $yyval = new ArrayArgument($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 56:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 57:
                         $yyval = new ArrayArgument($this->_yyastk[$yysp - (3 - 3)], $this->_yyastk[$yysp - (3 - 1)]); 
                        break;
                    case 58:
                         $yyval = new ArrayArgument($this->_yyastk[$yysp - (6 - 5)], $this->_yyastk[$yysp - (6 - 3)], true); 
                        break;
                    case 59:
                         $yyval = new BitwiseAnd($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 60:
                         $yyval = new BitwiseOr($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 61:
                         $yyval = new BitwiseXor($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 62:
                         $yyval = new BooleanAnd($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 63:
                         $yyval = new BooleanOr($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 64:
                         $yyval = new Coalesce($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 65:
                         $yyval = new Concat($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 66:
                         $yyval = new Div($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 67:
                         $yyval = new Equal($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 68:
                         $yyval = new Greater($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 69:
                         $yyval = new GreaterOrEqual($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 70:
                         $yyval = new Identical($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 71:
                         $yyval = new LogicalAnd($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 72:
                         $yyval = new LogicalOr($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 73:
                         $yyval = new LogicalXor($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 74:
                         $yyval = new Minus($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 75:
                         $yyval = new Mod($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 76:
                         $yyval = new Mul($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 77:
                         $yyval = new NotEqual($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 78:
                         $yyval = new NotIdentical($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 79:
                         $yyval = new Plus($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 80:
                         $yyval = new Pow($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 81:
                         $yyval = new ShiftLeft($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 82:
                         $yyval = new ShiftRight($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 83:
                         $yyval = new Smaller($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 84:
                         $yyval = new SmallerOrEqual($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 85:
                         $yyval = new Spaceship($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    }
                    $yysp -= $yyl;
                    $yyn = $this->_yylhs[$yyn];
                    if (($yyp = $this->_yygbase[$yyn] + $yysstk[$yysp]) >= 0
                        && $yyp < self::YYGLAST
                        && $this->_yygcheck[$yyp] == $yyn
                    ) {
                        $yystate = $this->_yygoto[$yyp];
                    } else {
                        $yystate = $this->_yygdefault[$yyn];
                    }

                    ++$yysp;

                    $yysstk[$yysp] = $yystate;
                    $this->_yyastk[$yysp] = $yyval;
                } else {
                    switch ($yyerrflag) {
                    case 0:
                        $this->_yyerror('syntax error');
                        // no break
                    case 1:
                    case 2:
                        $yyerrflag = 3;

                        while (!(($yyn = $this->_yybase[$yystate] + self::YYINTERRTOK) >= 0
                                && $yyn < self::YYLAST && $this->_yycheck[$yyn] == self::YYINTERRTOK
                                || ($yystate < self::YY2TBLSTATE
                                && ($yyn = $this->_yybase[$yystate + self::YYNLSTATES] + self::YYINTERRTOK) >= 0
                                && $yyn < self::YYLAST
                                && $this->_yycheck[$yyn] == self::YYINTERRTOK))
                        ) {
                            if ($yysp <= 0) {
                                $this->_yyflush();

                                return 1;
                            }
                            $yystate = $yysstk[--$yysp];
                        }
                        $yyn = $this->_yyaction[$yyn];
                        $yysstk[++$yysp] = $yystate = $yyn;
                        break;

                    case 3:
                        if ($yychar == 0) {
                            $this->_yyflush();

                            return 1;
                        }
                        $yychar = -1;
                        break;
                    }
                }

                if ($yystate < self::YYNLSTATES) {
                    break;
                }
                $yyn = $yystate - self::YYNLSTATES;
            }
        }
    }

    private function _yylex()
    {
        return $this->_lexer->getToken($this->_yylval);
    }

    private function _yyerror($msg)
    {
    }

    private function _yyflush()
    {
    }
}

