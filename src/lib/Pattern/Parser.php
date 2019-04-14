<?php

namespace Phinder\Pattern;

use Phinder\Pattern\Node\Arguments;
use Phinder\Pattern\Node\BooleanLiteral;
use Phinder\Pattern\Node\Conjunction;
use Phinder\Pattern\Node\Disjunction;
use Phinder\Pattern\Node\FloatLiteral;
use Phinder\Pattern\Node\Identifier;
use Phinder\Pattern\Node\IntegerLiteral;
use Phinder\Pattern\Node\Invocation;
use Phinder\Pattern\Node\Not;
use Phinder\Pattern\Node\NullLiteral;
use Phinder\Pattern\Node\StringLiteral;
use Phinder\Pattern\Node\Wildcard;

class Parser
{
    const YYERRTOK = 256;

    const T_COMMA = 257;

    const T_ARROW = 258;

    const T_DOUBLE_ARROW = 259;

    const T_ELLIPSIS = 260;

    const T_VERTICAL_BAR = 261;

    const T_AMPERSAND = 262;

    const T_EXCLAMATION = 263;

    const T_LEFT_PAREN = 264;

    const T_RIGHT_PAREN = 265;

    const T_NULL = 266;

    const T_BOOLEAN = 267;

    const T_INTEGER = 268;

    const T_FLOAT = 269;

    const T_STRING = 270;

    const T_BOOLEAN_LITERAL = 271;

    const T_FLOAT_LITERAL = 272;

    const T_INTEGER_LITERAL = 273;

    const T_STRING_LITERAL = 274;

    const T_IDENTIFIER = 275;

    const YYBADCH = 19;

    const YYMAXLEX = 276;

    const YYLAST = 24;

    const YY2TBLSTATE = 6;

    const YYNLSTATES = 14;

    const YYINTERRTOK = 1;

    const YYUNEXPECTED = 32767;

    const YYDEFAULT = -32766;

    const YYGLAST = 9;

    private $_yylval = null;

    private $_lexer = null;

    private $_yyastk = null;

    private $_yytranslate = [
            0,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,   19,   19,   19,   19,
           19,   19,   19,   19,   19,   19,    1,    2,   19,   19,
            3,    4,    5,    6,    7,    8,    9,   10,   11,   12,
           13,   14,   15,   16,   17,   18
    ];

    private $_yyaction = [
            3,    0,   40,   42,   44,   46,   48,   41,   45,   43,
           47,    7,   39,    2,    4,    6,   23,    5,    0,    6,
            0,    1,    0,   32
    ];

    private $_yycheck = [
            7,    0,    9,   10,   11,   12,   13,   14,   15,   16,
           17,   18,    3,    2,    4,    6,    8,    5,   -1,    6,
           -1,    7,   -1,    8
    ];

    private $_yybase = [
           13,    9,    9,   13,   13,   13,   -7,   14,    1,   10,
           12,    8,   15,   11,   -7,   -7,   -7,   -7,   -7,   -7
    ];

    private $_yydefault = [
        32767,   19,32767,32767,32767,32767,32767,   17,32767,    2,
            4,32767,32767,   21
    ];

    private $_yygoto = [
           36,   15,   19,    0,   11,   17,    0,    0,   21
    ];

    private $_yygcheck = [
           15,    2,    3,   -1,    2,    2,   -1,   -1,    5
    ];

    private $_yygbase = [
            0,    0,    1,   -3,    0,    2,    0,    0,    0,    0,
            0,    0,    0,    0,    0,   -2,    0,    0
    ];

    private $_yygdefault = [
        -32768,    8,   37,    9,   10,   20,   22,   24,   25,   26,
           27,   28,   29,   30,   12,   34,   13,   38
    ];

    private $_yylhs = [
            0,    1,    2,    2,    3,    3,    4,    4,    5,    5,
            6,    6,    6,    6,    6,    6,    6,    7,    8,   14,
           14,   15,   15,   16,   16,   17,    9,   10,   10,   11,
           11,   12,   12,   13,   13
    ];

    private $_yylen = [
            1,    1,    1,    3,    1,    3,    1,    2,    1,    3,
            1,    1,    1,    1,    1,    1,    1,    1,    4,    0,
            1,    1,    3,    1,    1,    1,    1,    1,    1,    1,
            1,    1,    1,    1,    1
    ];

    public static function create()
    {
        return new Parser();
    }

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
                         $yyval = new Not($this->_yyastk[$yysp - (2 - 2)]); 
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
                         $yyval = ($this->_yyastk[$yysp - (1 - 1)] === '_') ? new Wildcard() : new Identifier($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 18:
                         $yyval = new Invocation($this->_yyastk[$yysp - (4 - 1)], $this->_yyastk[$yysp - (4 - 3)]); 
                        break;
                    case 19:
                         $yyval = new Arguments(); 
                        break;
                    case 20:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 21:
                         $yyval = new Arguments($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 22:
                         $yyval = new Arguments($this->_yyastk[$yysp - (3 - 1)], $this->_yyastk[$yysp - (3 - 3)]); 
                        break;
                    case 23:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 24:
                         $yyval = $this->_yyastk[$yysp - (1 - 1)]; 
                        break;
                    case 25:
                         $yyval = new Wildcard(true); 
                        break;
                    case 26:
                         $yyval = new NullLiteral(); 
                        break;
                    case 27:
                         $yyval = new BooleanLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 28:
                         $yyval = new BooleanLiteral(); 
                        break;
                    case 29:
                         $yyval = new IntegerLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 30:
                         $yyval = new IntegerLiteral(); 
                        break;
                    case 31:
                         $yyval = new FloatLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 32:
                         $yyval = new FloatLiteral(); 
                        break;
                    case 33:
                         $yyval = new StringLiteral($this->_yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 34:
                         $yyval = new StringLiteral(); 
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

