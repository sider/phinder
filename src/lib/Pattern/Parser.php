<?php

namespace Phinder\Pattern;

use Phinder\Pattern\Node\LogicalOperation\Conjunction;
use Phinder\Pattern\Node\LogicalOperation\Disjunction;
use Phinder\Pattern\Node\LogicalOperation\Negation;
use Phinder\Pattern\Node\Scalar\FloatLiteral;
use Phinder\Pattern\Node\Scalar\IntegerLiteral;
use Phinder\Pattern\Node\Scalar\StringLiteral;
use Phinder\Pattern\Node\Arguments;
use Phinder\Pattern\Node\ArrayElements;
use Phinder\Pattern\Node\ArrayLiteral;
use Phinder\Pattern\Node\BooleanLiteral;
use Phinder\Pattern\Node\Ellipsis;
use Phinder\Pattern\Node\Identifier;
use Phinder\Pattern\Node\Invocation;
use Phinder\Pattern\Node\KeyValuePair;
use Phinder\Pattern\Node\MethodInvocation;
use Phinder\Pattern\Node\NullLiteral;

class Parser
{
    const YYERRTOK = 256;

    const T_COMMA = 257;

    const T_ARROW = 258;

    const T_ARRAY = 259;

    const T_DOUBLE_ARROW = 260;

    const T_ELLIPSIS = 261;

    const T_VERTICAL_BAR = 262;

    const T_AMPERSAND = 263;

    const T_EXCLAMATION = 264;

    const T_LEFT_PAREN = 265;

    const T_RIGHT_PAREN = 266;

    const T_LEFT_BRACKET = 267;

    const T_RIGHT_BRACKET = 268;

    const T_NULL = 269;

    const T_BOOLEAN = 270;

    const T_INTEGER = 271;

    const T_FLOAT = 272;

    const T_STRING = 273;

    const T_BOOLEAN_LITERAL = 274;

    const T_FLOAT_LITERAL = 275;

    const T_INTEGER_LITERAL = 276;

    const T_STRING_LITERAL = 277;

    const T_IDENTIFIER = 278;

    const YYBADCH = 24;

    const YYMAXLEX = 279;

    const YYLAST = 34;

    const YY2TBLSTATE = 15;

    const YYNLSTATES = 32;

    const YYINTERRTOK = 1;

    const YYUNEXPECTED = 32767;

    const YYDEFAULT = -32766;

    const YYGLAST = 21;

    private $_yylval = null;

    private $_lexer = null;

    private $_yyastk = null;

    private $_yytranslate = [
            0,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,   24,   24,   24,   24,
           24,   24,   24,   24,   24,   24,    1,    2,    3,    4,
            5,    6,    7,    8,    9,   10,   11,   12,   13,   14,
           15,   16,   17,   18,   19,   20,   21,   22,   23
    ];

    private $_yyaction = [
           16,    0,   -2,   10,    4,    6,    7,    5,    1,   15,
           61,   63,   65,   67,   69,   62,   66,   64,   68,   51,
           -6,   60,    9,    8,   -6,   -6,   71,   51,    2,    3,
            0,   70,   52,   41
    ];

    private $_yycheck = [
            4,    0,    3,    5,    2,    9,   10,    2,   12,    3,
           14,   15,   16,   17,   18,   19,   20,   21,   22,   23,
            3,    6,    8,    7,    7,    8,   13,   23,   10,   10,
           -1,   11,   11,   11
    ];

    private $_yybase = [
           -4,   15,   15,   15,   15,   15,   -4,   -4,   -4,   -4,
           -4,   17,   22,   -2,   -1,    4,   18,    1,    6,   16,
           14,   19,    6,   13,    2,   20,   19,    6,    6,   21,
            5,    6,    0,   -4,   -4,   -4,   -4,   -4,    0,    0,
            0,    0,    0,    0,    6,    6,   16
    ];

    private $_yydefault = [
        32767,   40,   40,   22,32767,32767,32767,32767,32767,32767,
        32767,    7,32767,   44,    5,32767,32767,32767,    1,    2,
            4,   10,32767,32767,   42,32767,32767,    3,   26,32767,
           24,   46
    ];

    private $_yygoto = [
           59,   18,   59,   75,   28,   25,   28,   22,   12,   27,
           22,   31,   57,   14,   11,    0,    0,   26,    0,    0,
           53
    ];

    private $_yygcheck = [
           19,    2,   19,   21,    2,   20,    2,    2,    2,    2,
            2,    2,   17,    3,    5,   -1,   -1,    7,   -1,   -1,
            8
    ];

    private $_yygbase = [
            0,    0,    1,    4,    0,    8,    0,    2,    5,    0,
            0,    0,    0,    0,    0,    0,    0,    7,    0,   -3,
            3,   -1,    0
    ];

    private $_yygdefault = [
        -32768,   17,   13,   19,   20,   38,   40,   21,   43,   44,
           45,   46,   47,   48,   49,   50,   29,   55,   30,   77,
           23,   73,   24
    ];

    private $_yylhs = [
            0,    1,    2,    2,    3,    3,    4,    4,    5,    5,
            6,    6,    6,    6,    6,    6,    6,    6,    6,    7,
            8,    9,   16,   16,   17,   17,   18,   18,   19,   10,
           11,   11,   12,   12,   13,   13,   14,   14,   15,   15,
           20,   20,   21,   21,   22,   22,   22
    ];

    private $_yylen = [
            1,    1,    1,    3,    1,    3,    1,    2,    1,    3,
            1,    1,    1,    1,    1,    1,    1,    1,    1,    1,
            4,    3,    0,    1,    1,    3,    1,    1,    1,    1,
            1,    1,    1,    1,    1,    1,    1,    1,    4,    3,
            0,    1,    1,    3,    1,    1,    3
    ];

    public function parse($string)
    {
        $this->_lexer = new Lexer($string);
        $status = $this->_yyparse();

        if ($status === 0) {
            return $this->_yyastk[1];
        }

        return null;
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
                         $yyval = new Identifier($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 20:
                         $yyval = new Invocation($this->_yyastk[$yysp - (4 - 1)], $this->_yyastk[$yysp - (4 - 3)]); 
                        break;
                    case 21:
                         $yyval = new MethodInvocation($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 22:
                         $yyval = new Arguments(); 
                        break;
                    case 23:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 24:
                         $yyval = new Arguments($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 25:
                         $yyval = new Arguments($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 26:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 27:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 28:
                         $yyval = new Ellipsis(); 
                        break;
                    case 29:
                         $yyval = new NullLiteral(); 
                        break;
                    case 30:
                         $yyval = new BooleanLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 31:
                         $yyval = new BooleanLiteral(); 
                        break;
                    case 32:
                         $yyval = new IntegerLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 33:
                         $yyval = new IntegerLiteral(); 
                        break;
                    case 34:
                         $yyval = new FloatLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 35:
                         $yyval = new FloatLiteral(); 
                        break;
                    case 36:
                         $yyval = new StringLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 37:
                         $yyval = new StringLiteral(); 
                        break;
                    case 38:
                         $yyval = new ArrayLiteral(false, $this->_yyastk[$yysp - (4 - 3)]); 
                        break;
                    case 39:
                         $yyval = new ArrayLiteral(true, $this->_yyastk[$yysp - (3 - 2)]); 
                        break;
                    case 40:
                         $yyval = new ArrayElements(); 
                        break;
                    case 41:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 42:
                         $yyval = new ArrayElements($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 43:
                         $yyval = new ArrayElements($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 44:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 45:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 46:
                         $yyval = new KeyValuePair($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
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

    private function _readLine()
    {
        $line = array_shift($this->_inputLines);

        return $line === null ? false : $line;
    }
}

