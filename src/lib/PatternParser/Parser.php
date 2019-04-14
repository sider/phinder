<?php

namespace Phinder\PatternParser;

use Phinder\PatternParser\Node\Arguments;
use Phinder\PatternParser\Node\Conjunction;
use Phinder\PatternParser\Node\Disjunction;
use Phinder\PatternParser\Node\Identifier;
use Phinder\PatternParser\Node\Invocation;
use Phinder\PatternParser\Node\Not;
use Phinder\PatternParser\Node\Wildcard;

class Parser
{
    const YYERRTOK = 256;

    const ARROW = 257;

    const DOUBLE_ARROW = 258;

    const ELLIPSIS = 259;

    const NULL = 260;

    const BOOLEAN = 261;

    const IDENTIFIER = 262;

    const FLOAT = 263;

    const INTEGER = 264;

    const STRING = 265;

    const YYBADCH = 21;

    const YYMAXLEX = 266;

    const YYLAST = 31;

    const YY2TBLSTATE = 6;

    const YYNLSTATES = 19;

    const YYINTERRTOK = 1;

    const YYUNEXPECTED = 32767;

    const YYDEFAULT = -32766;

    const YYGLAST = 9;

    private $_yylval = null;

    private $_lexer = null;

    private $_yytranslate = [
            0,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   11,   21,   21,   21,   21,   10,   21,
           12,   13,   21,   21,   15,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   16,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   14,   21,   21,   17,   21,
           21,   21,   19,   21,   21,   18,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   20,   21,   21,   21,   21,
           21,   21,   21,   21,    9,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,   21,   21,   21,   21,
           21,   21,   21,   21,   21,   21,    1,   21,   21,    2,
            3,    4,    5,    6,    7,    8
    ];

    private $_yyaction = [
           47,   48,    8,   52,   50,   54,    0,    6,    4,    3,
           46,   37,    5,    7,   28,   13,   14,   15,   16,    6,
           39,    1,    0,    0,    0,    2,    0,   49,   53,   51,
           55
    ];

    private $_yycheck = [
            3,    4,    5,    6,    7,    8,    0,   11,    9,   12,
            2,   14,   10,   16,   13,   17,   18,   19,   20,   11,
           13,   12,   -1,   -1,   -1,   15,   -1,   16,   16,   16,
           16
    ];

    private $_yybase = [
           -4,    8,    8,   -4,   -4,   -4,   -3,   -2,    9,    6,
           -1,    2,    1,   11,   13,   12,   14,    7,   10,   -3,
           -3,   -3,   -3,   -3,   -3
    ];

    private $_yydefault = [
        32767,   21,32767,32767,32767,32767,32767,32767,   19,32767,
            2,    4,32767,32767,32767,32767,32767,32767,   23
    ];

    private $_yygoto = [
           43,   20,   24,    0,   12,   22,    0,    0,   26
    ];

    private $_yygcheck = [
           16,    2,    3,   -1,    2,    2,   -1,   -1,    5
    ];

    private $_yygbase = [
            0,    0,    1,   -3,    0,    2,    0,    0,    0,    0,
            0,    0,    0,    0,    0,    0,   -2,    0,    0
    ];

    private $_yygdefault = [
        -32768,    9,   44,   10,   11,   25,   27,   29,   30,   31,
           32,   33,   34,   35,   36,   17,   41,   18,   45
    ];

    private $_yylhs = [
            0,    1,    2,    2,    3,    3,    4,    4,    5,    5,
            6,    6,    6,    6,    6,    6,    6,    6,    7,    8,
            9,   15,   15,   16,   16,   17,   17,   18,   10,   11,
           11,   12,   12,   13,   13,   14,   14
    ];

    private $_yylen = [
            1,    1,    1,    3,    1,    3,    1,    2,    1,    3,
            1,    1,    1,    1,    1,    1,    1,    1,    1,    1,
            4,    0,    1,    1,    3,    1,    1,    1,    1,    1,
            3,    1,    3,    1,    3,    1,    3
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
                         $yyval = new BooleanLiteral(); 
                        break;
                    case 31:
                         $yyval = new IntegerLiteral($yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 32:
                         $yyval = new IntegerLiteral(); 
                        break;
                    case 33:
                         $yyval = new FloatLiteral($yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 34:
                         $yyval = new FloatLiteral(); 
                        break;
                    case 35:
                         $yyval = new StringLiteral($yyastk[$yysp - (1 - 1)]); 
                        break;
                    case 36:
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
        return $this->getToken($this->_yylval);
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
