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
use Phinder\Pattern\Node;

class Parser
{
    const YYERRTOK = 256;

    const T_COMMA = 257;

    const T_ARROW = 258;

    const T_ARRAY = 259;

    const T_DOUBLE_ARROW = 260;

    const T_ELLIPSIS = 261;

    const T_DOT = 262;

    const T_TRIPLE_VERTICAL_BAR = 263;

    const T_TRIPLE_AMPERSAND = 264;

    const T_EXCLAMATION = 265;

    const T_LEFT_PAREN = 266;

    const T_RIGHT_PAREN = 267;

    const T_LEFT_BRACKET = 268;

    const T_RIGHT_BRACKET = 269;

    const T_NULL = 270;

    const T_BOOLEAN = 271;

    const T_INTEGER = 272;

    const T_FLOAT = 273;

    const T_STRING = 274;

    const T_BOOLEAN_LITERAL = 275;

    const T_FLOAT_LITERAL = 276;

    const T_INTEGER_LITERAL = 277;

    const T_STRING_LITERAL = 278;

    const T_IDENTIFIER = 279;

    const YYBADCH = 25;

    const YYMAXLEX = 280;

    const YYLAST = 50;

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
            0,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,   25,   25,   25,   25,
           25,   25,   25,   25,   25,   25,    1,    2,    3,    4,
            5,    6,    7,    8,    9,   10,   11,   12,   13,   14,
           15,   16,   17,   18,   19,   20,   21,   22,   23,   24
    ];

    private $_yyaction = [
           29,   70,    0,   15,   -2,    9,    7,    8,   -2,    1,
           50,   71,   73,   75,   77,   79,   72,   76,   74,   78,
           61,   -6,    5,   70,   28,   -6,   -6,   -6,   10,    6,
           14,   11,   61,   12,   81,    0,    0,    2,    0,    3,
            4,   13,    0,   62,   63,   50,   90,    0,    0,   82
    ];

    private $_yycheck = [
            4,    6,    0,    5,    3,   10,   10,   11,    7,   13,
           12,   15,   16,   17,   18,   19,   20,   21,   22,   23,
           24,    3,    2,    6,    3,    7,    8,    9,    7,    2,
            5,    8,   24,    9,   12,   -1,   -1,   11,   -1,   11,
           11,   11,   -1,   12,   12,   12,   12,   -1,   -1,   14
    ];

    private $_yybase = [
           -4,   -5,   -5,   17,   17,   -5,   17,   -4,   -4,   30,
           -4,   -4,   -4,   -4,   -4,   -4,   18,   -2,   33,   25,
            1,   34,   21,   21,   21,   21,   21,   21,    8,   26,
            2,   23,   24,   28,   35,   20,   22,   29,   31,   27,
           32,    0,   -4,   -4,   -4,   -4,   -4,   -4,    0,    0,
           -4,    0,    0,    0,    0,    0,    0,    0,   21,   21,
           21,   23,   21
    ];

    private $_yydefault = [
        32767,   42,   42,   23,   23,32767,32767,32767,32767,32767,
        32767,32767,32767,32767,32767,32767,    7,32767,32767,   46,
            5,32767,    1,32767,   39,    3,   27,   48,32767,32767,
        32767,    2,    4,   10,32767,   44,32767,32767,32767,   25,
        32767
    ];

    private $_yygoto = [
           16,   22,   16,   40,   26,   26,   36,   26,   23,   18,
           23,   24,   25,   23,   17,   27,   21,   69,   69,   67,
           69,   86,   20,   37
    ];

    private $_yygcheck = [
            5,    2,    5,   17,    2,    2,   21,    2,    2,    2,
            2,    2,    2,    2,    2,    2,    2,   20,   20,   18,
           20,   22,    3,    7
    ];

    private $_yygbase = [
            0,    0,    1,   10,    0,   -7,    0,   -5,    0,    0,
            0,    0,    0,    0,    0,    0,    0,   -1,   13,    0,
           14,    4,   16,    0
    ];

    private $_yygdefault = [
        -32768,   30,   19,   31,   32,   47,   49,   33,   52,   53,
           54,   55,   56,   57,   58,   59,   60,   38,   65,   39,
           88,   34,   84,   35
    ];

    private $_yylhs = [
            0,    1,    2,    2,    3,    3,    4,    4,    5,    5,
            6,    6,    6,    6,    6,    6,    6,    6,    6,    6,
            7,    8,    9,   17,   17,   18,   18,   19,   19,   20,
           11,   12,   12,   13,   13,   14,   14,   15,   15,   16,
           10,   10,   21,   21,   22,   22,   23,   23,   23,   23
    ];

    private $_yylen = [
            1,    1,    1,    3,    1,    3,    1,    2,    1,    3,
            1,    1,    1,    1,    1,    1,    1,    1,    1,    1,
            1,    4,    6,    0,    1,    1,    3,    1,    1,    1,
            1,    1,    1,    1,    1,    1,    1,    1,    1,    3,
            4,    3,    0,    1,    1,    3,    1,    1,    3,    6
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
                         $yyval = new Identifier($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 21:
                         $yyval = new FunctionCall($this->_yyastk[$yysp - (4 - 1)], $this->_yyastk[$yysp - (4 - 3)]); 
                        break;
                    case 22:
                         $yyval = new MethodCall($this->_yyastk[$yysp - (6 - 1)], $this->_yyastk[$yysp - (6 - 3)], $this->_yyastk[$yysp - (6 - 5)]); 
                        break;
                    case 23:
                         $yyval = []; 
                        break;
                    case 24:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 25:
                         $yyval = [$this->_yyastk[$yysp - (1 - 1)]]; 
                        break;
                    case 26:
                         $yyval = array_merge([$this->_yyastk[$yysp - (3 - 1)]], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 27:
                         $yyval = new Argument($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 28:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 29:
                         $yyval = Node::ELLIPSIS; 
                        break;
                    case 30:
                         $yyval = new NullLiteral(); 
                        break;
                    case 31:
                         $yyval = new BooleanLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 32:
                         $yyval = new BooleanLiteral(); 
                        break;
                    case 33:
                         $yyval = new IntegerLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 34:
                         $yyval = new IntegerLiteral(); 
                        break;
                    case 35:
                         $yyval = new FloatLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 36:
                         $yyval = new FloatLiteral(); 
                        break;
                    case 37:
                         $yyval = new StringLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 38:
                         $yyval = new StringLiteral(); 
                        break;
                    case 39:
                         $yyval = new StringConcatenation($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 40:
                         $yyval = new ArrayCall($this->_yyastk[$yysp - (4 - 3)]); 
                        break;
                    case 41:
                         $yyval = new ArrayCall($this->_yyastk[$yysp - (3 - 2)]); 
                        break;
                    case 42:
                         $yyval = []; 
                        break;
                    case 43:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 44:
                         $yyval = [$this->_yyastk[$yysp - (1 - 1)]]; 
                        break;
                    case 45:
                         $yyval = array_merge([$this->_yyastk[$yysp - (3 - 1)]], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 46:
                         $yyval = new ArrayArgument($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 47:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 48:
                         $yyval = new ArrayArgument($this->_yyastk[$yysp - (3 - 3)], $this->_yyastk[$yysp - (3 - 1)]); 
                        break;
                    case 49:
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

