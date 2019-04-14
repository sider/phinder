<?php

namespace Phinder\Pattern;

use Phinder\Pattern\Node\Arguments;
use Phinder\Pattern\Node\Conjunction;
use Phinder\Pattern\Node\Disjunction;
use Phinder\Pattern\Node\Identifier;
use Phinder\Pattern\Node\Invocation;
use Phinder\Pattern\Node\Not;
use Phinder\Pattern\Node\Wildcard;

class Parser
{
    const YYERRTOK = 256;

    const T_ARROW = 257;

    const T_DOUBLE_ARROW = 258;

    const T_ELLIPSIS = 259;

    const T_VERTICAL_BAR = 260;

    const T_AMPERSAND = 261;

    const T_EXCLAMATION = 262;

    const T_LEFT_PAREN = 263;

    const T_RIGHT_PAREN = 264;

    const T_IDENTIFIER = 265;

    const T_UNSERSCORE = 266;

    const T_COMMA = 267;

    const T_NULL = 268;

    const T_BOOLEAN = 269;

    const T_FLOAT = 270;

    const T_INTEGER = 271;

    const T_STRING = 272;

    const YYBADCH = 16;

    const YYMAXLEX = 273;

    const YYLAST = 21;

    const YY2TBLSTATE = 6;

    const YYNLSTATES = 14;

    const YYINTERRTOK = 1;

    const YYUNEXPECTED = 32767;

    const YYDEFAULT = -32766;

    const YYGLAST = 9;

    private $_yylval = null;

    private $_lexer = null;

    private $_yytranslate = [
            0,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,   16,   16,   16,   16,
           16,   16,   16,   16,   16,   16,    1,   16,   16,    2,
            3,    4,    5,    6,    7,    8,    9,   10,   11,   12,
           13,   14,   15
    ];

    private $_yyaction = [
            3,   41,    7,   32,    6,   42,   43,   45,   44,   46,
            0,    4,    6,    5,    2,    0,    0,    1,    0,   34,
           23
    ];

    private $_yycheck = [
            6,    2,    8,    9,    5,   11,   12,   13,   14,   15,
            0,    3,    5,    4,   10,   -1,   -1,    6,   -1,    7,
            7
    ];

    private $_yybase = [
            7,   -1,   -1,    7,    7,    7,   -6,   11,   10,    8,
            9,   13,   12,    4,   -6,   -6,   -6,   -6,   -6,   -6
    ];

    private $_yydefault = [
        32767,   21,32767,32767,32767,32767,32767,   19,32767,    2,
            4,32767,32767,   23
    ];

    private $_yygoto = [
           38,   15,   19,    0,   11,   17,    0,    0,   21
    ];

    private $_yygcheck = [
           16,    2,    3,   -1,    2,    2,   -1,   -1,    5
    ];

    private $_yygbase = [
            0,    0,    1,   -3,    0,    2,    0,    0,    0,    0,
            0,    0,    0,    0,    0,    0,   -2,    0,    0
    ];

    private $_yygdefault = [
        -32768,    8,   39,    9,   10,   20,   22,   24,   25,   26,
           27,   28,   29,   30,   31,   12,   36,   13,   40
    ];

    private $_yylhs = [
            0,    1,    2,    2,    3,    3,    4,    4,    5,    5,
            6,    6,    6,    6,    6,    6,    6,    6,    7,    8,
            9,   15,   15,   16,   16,   17,   17,   18,   10,   11,
           12,   13,   14
    ];

    private $_yylen = [
            1,    1,    1,    3,    1,    3,    1,    2,    1,    3,
            1,    1,    1,    1,    1,    1,    1,    1,    1,    1,
            4,    0,    1,    1,    3,    1,    1,    1,    1,    1,
            1,    1,    1
    ];

    public static function create()
    {
        return new Parser();
    }

    public function parse($string)
    {
        $this->_lexer = new Lexer($string);

        return $this->_yyparse();
    }

    private function _yyparse()
    {
        $yyastk = [];
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
                        $yyastk[$yysp] = $this->_yylval;
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
                    $yyval = isset($yyastk[$n]) ? $yyastk[$n] : null;
                    switch ($yyn) {
                    case 1:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 2:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 3:
                         $yyval = new Disjunction($yyastk[$yysp - (3 - 1)], $yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 4:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 5:
                         $yyval = new Conjunction($yyastk[$yysp - (3 - 1)], $yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 6:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 7:
                         $yyval = new Not($yyastk[$yysp - (2 - 1)]); 
                        break;
                    case 8:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 9:
                         $yyval = $yyastk[$yysp - (3 - 2)]; 
                        break;
                    case 10:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 11:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 12:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 13:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 14:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 15:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 16:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 17:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 18:
                         $yyval = new Wildcard(); 
                        break;
                    case 19:
                         $yyval = new Identifier($yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 20:
                         $yyval = new Invocation($yyastk[$yysp - (4 - 1)], $yyastk[$yysp - (4 - 3)]); 
                        break;
                    case 21:
                         $yyval = new Arguments(); 
                        break;
                    case 22:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 23:
                         $yyval = new Arguments($yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 24:
                         $yyval = new Arguments($yyastk[$yysp - (3 - 1)], $yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 25:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 26:
                         $yyval = $yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 27:
                         $yyval = new Wildcard(true); 
                        break;
                    case 28:
                         $yyval = new NullLiteral(); 
                        break;
                    case 29:
                         $yyval = new BooleanLiteral($yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 30:
                         $yyval = new IntegerLiteral($yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 31:
                         $yyval = new FloatLiteral($yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 32:
                         $yyval = new StringLiteral($yyastk[$yysp - (1 - 1)]); 
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
                    $yyastk[$yysp] = $yyval;
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
