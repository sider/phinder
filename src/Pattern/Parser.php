<?php

namespace Phinder\Pattern;

use Phinder\Error\InvalidPattern;
use Phinder\Pattern\Node\BinaryOperation\StringConcatenation;
use Phinder\Pattern\Node\Call\ArrayCall;
use Phinder\Pattern\Node\Call\FunctionCall;
use Phinder\Pattern\Node\Call\MethodCall;
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
use Phinder\Pattern\Node\This;
use Phinder\Pattern\Node;

class Parser
{
    const YYERRTOK = 256;

    const T_THIS = 257;

    const T_COMMA = 258;

    const T_ARROW = 259;

    const T_ARRAY = 260;

    const T_DOUBLE_ARROW = 261;

    const T_ELLIPSIS = 262;

    const T_DOT = 263;

    const T_TRIPLE_VERTICAL_BAR = 264;

    const T_TRIPLE_AMPERSAND = 265;

    const T_EXCLAMATION = 266;

    const T_LEFT_PAREN = 267;

    const T_RIGHT_PAREN = 268;

    const T_LEFT_BRACKET = 269;

    const T_RIGHT_BRACKET = 270;

    const T_NULL = 271;

    const T_BOOLEAN = 272;

    const T_INTEGER = 273;

    const T_FLOAT = 274;

    const T_STRING = 275;

    const T_BOOLEAN_LITERAL = 276;

    const T_FLOAT_LITERAL = 277;

    const T_INTEGER_LITERAL = 278;

    const T_STRING_LITERAL = 279;

    const T_IDENTIFIER = 280;

    const YYBADCH = 26;

    const YYMAXLEX = 281;

    const YYLAST = 48;

    const YY2TBLSTATE = 22;

    const YYNLSTATES = 41;

    const YYINTERRTOK = 1;

    const YYUNEXPECTED = 32767;

    const YYDEFAULT = -32766;

    const YYGLAST = 24;

    private $_yylval = null;

    private $_lexer = null;

    private $_yyastk = null;

    private $_yytranslate = [
            0,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,   26,   26,   26,   26,
           26,   26,   26,   26,   26,   26,    1,    2,    3,    4,
            5,    6,    7,    8,    9,   10,   11,   12,   13,   14,
           15,   16,   17,   18,   19,   20,   21,   22,   23,   24,
           25
    ];

    private $_yyaction = [
           64,   -6,    0,   29,    5,   -6,   -6,   -6,    6,    7,
            8,   74,    1,   14,   75,   77,   79,   81,   83,   76,
           80,   78,   82,   63,   15,   -2,   28,   12,   11,   -2,
           10,   50,   74,   85,   63,    2,    9,   65,    3,    4,
           13,    0,   66,   50,   94,    0,    0,   86
    ];

    private $_yycheck = [
            2,    4,    0,    5,    3,    8,    9,   10,    3,   11,
           12,    7,   14,    6,   16,   17,   18,   19,   20,   21,
           22,   23,   24,   25,    6,    4,    4,   10,    9,    8,
            8,   13,    7,   13,   25,   12,   11,   13,   12,   12,
           12,   -1,   13,   13,   13,   -1,   -1,   15
    ];

    private $_yybase = [
           -2,   25,   25,    4,    4,   25,    4,   -2,   -2,   28,
           -2,   -2,   -2,   -2,   -2,   -2,   -3,   18,   30,    7,
           21,   31,   22,   22,   22,   22,   22,   22,    9,   23,
            2,   19,   17,   26,   32,    1,   20,   27,   24,    5,
           29,    0,   -2,   -2,   -2,   -2,   -2,   -2,    0,    0,
           -2,    0,    0,    0,    0,    0,    0,    0,   22,   22,
           22,   19,   22
    ];

    private $_yydefault = [
        32767,   46,   46,   27,   27,32767,32767,32767,32767,32767,
        32767,32767,32767,32767,32767,32767,    7,32767,32767,   50,
            5,32767,    1,32767,   43,    3,   31,   52,32767,32767,
        32767,    2,    4,   10,32767,   48,32767,   26,32767,   29,
        32767
    ];

    private $_yygoto = [
           16,   22,   16,   40,   26,   26,   36,   26,   23,   18,
           23,   24,   25,   23,   17,   27,   21,   73,   73,   71,
           73,   90,   20,   37
    ];

    private $_yygcheck = [
            5,    2,    5,   19,    2,    2,   23,    2,    2,    2,
            2,    2,    2,    2,    2,    2,    2,   22,   22,   20,
           22,   24,    3,    7
    ];

    private $_yygbase = [
            0,    0,    1,   10,    0,   -7,    0,   -5,    0,    0,
            0,    0,    0,    0,    0,    0,    0,    0,    0,   -1,
           13,    0,   14,    4,   16,    0
    ];

    private $_yygdefault = [
        -32768,   30,   19,   31,   32,   47,   49,   33,   52,   53,
           54,   55,   56,   57,   58,   59,   60,   61,   62,   38,
           69,   39,   92,   34,   88,   35
    ];

    private $_yylhs = [
            0,    1,    2,    2,    3,    3,    4,    4,    5,    5,
            6,    6,    6,    6,    6,    6,    6,    6,    6,    6,
            6,    6,    7,    8,    9,   10,   11,   19,   19,   20,
           20,   21,   21,   22,   13,   14,   14,   15,   15,   16,
           16,   17,   17,   18,   12,   12,   23,   23,   24,   24,
           25,   25,   25,   25
    ];

    private $_yylen = [
            1,    1,    1,    3,    1,    3,    1,    2,    1,    3,
            1,    1,    1,    1,    1,    1,    1,    1,    1,    1,
            1,    1,    1,    1,    4,    6,    3,    0,    1,    1,
            3,    1,    1,    1,    1,    1,    1,    1,    1,    1,
            1,    1,    1,    3,    4,    3,    0,    1,    1,    3,
            1,    1,    3,    6
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
                         $yyval = new Identifier($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 23:
                         $yyval = new This(); 
                        break;
                    case 24:
                         $yyval = new FunctionCall($this->_yyastk[$yysp - (4 - 1)], $this->_yyastk[$yysp - (4 - 3)]); 
                        break;
                    case 25:
                         $yyval = new MethodCall($this->_yyastk[$yysp - (6 - 1)], $this->_yyastk[$yysp - (6 - 3)], $this->_yyastk[$yysp - (6 - 5)]); 
                        break;
                    case 26:
                         $yyval = new PropertyAccess($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 27:
                         $yyval = []; 
                        break;
                    case 28:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 29:
                         $yyval = [$this->_yyastk[$yysp - (1 - 1)]]; 
                        break;
                    case 30:
                         $yyval = array_merge([$this->_yyastk[$yysp - (3 - 1)]], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 31:
                         $yyval = new Argument($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 32:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 33:
                         $yyval = Node::ELLIPSIS; 
                        break;
                    case 34:
                         $yyval = new NullLiteral(); 
                        break;
                    case 35:
                         $yyval = new BooleanLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 36:
                         $yyval = new BooleanLiteral(); 
                        break;
                    case 37:
                         $yyval = new IntegerLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 38:
                         $yyval = new IntegerLiteral(); 
                        break;
                    case 39:
                         $yyval = new FloatLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 40:
                         $yyval = new FloatLiteral(); 
                        break;
                    case 41:
                         $yyval = new StringLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 42:
                         $yyval = new StringLiteral(); 
                        break;
                    case 43:
                         $yyval = new StringConcatenation($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 44:
                         $yyval = new ArrayCall($this->_yyastk[$yysp - (4 - 3)]); 
                        break;
                    case 45:
                         $yyval = new ArrayCall($this->_yyastk[$yysp - (3 - 2)]); 
                        break;
                    case 46:
                         $yyval = []; 
                        break;
                    case 47:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 48:
                         $yyval = [$this->_yyastk[$yysp - (1 - 1)]]; 
                        break;
                    case 49:
                         $yyval = array_merge([$this->_yyastk[$yysp - (3 - 1)]], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 50:
                         $yyval = new ArrayArgument($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 51:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 52:
                         $yyval = new ArrayArgument($this->_yyastk[$yysp - (3 - 3)], $this->_yyastk[$yysp - (3 - 1)]); 
                        break;
                    case 53:
                         $yyval = new ArrayArgument($this->_yyastk[$yysp - (6 - 5)], $this->_yyastk[$yysp - (6 - 3)], true); 
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

