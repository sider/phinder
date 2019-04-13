<?php

namespace Phinder\PatternParser;

use Phinder\PatternParser\Node\Conjunction;
use Phinder\PatternParser\Node\Disjunction;
use Phinder\PatternParser\Node\Not;
use Phinder\PatternParser\Node\Wildcard;

class PatternParser
{
    const YYERRTOK = 256;

    const YYBADCH = 8;

    const YYMAXLEX = 257;

    const YYLAST = 11;

    const YY2TBLSTATE = 4;

    const YYNLSTATES = 9;

    const YYINTERRTOK = 1;

    const YYUNEXPECTED = 32767;

    const YYDEFAULT = -32766;

    const YYGLAST = 5;

    private $_yylval = null;

    private $_lexbuf = '';

    private $_inputLines = null;

    private $_yytranslate = [
            0,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    4,    8,    8,    8,    8,    3,    8,
            5,    6,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    7,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    2,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    8,    8,    8,    8,
            8,    8,    8,    8,    8,    8,    1
    ];

    private $_yyaction = [
            1,    0,   19,    4,    2,    0,    3,    0,    0,    0,
           18
    ];

    private $_yycheck = [
            5,    0,    7,    4,    2,   -1,    3,   -1,   -1,   -1,
            6
    ];

    private $_yybase = [
           -1,   -1,   -1,   -1,   -5,    1,    2,    3,    4,   -5,
           -5,   -5,   -5
    ];

    private $_yydefault = [
        32767,32767,32767,32767,32767,32767,    2,    4,32767
    ];

    private $_yygoto = [
            8,   12,   16,    0,   14
    ];

    private $_yygcheck = [
            2,    2,    5,   -1,    3
    ];

    private $_yygbase = [
            0,    0,   -1,    1,    0,   -2,    0
    ];

    private $_yygdefault = [
        -32768,    5,   10,    6,    7,   15,   17
    ];

    private $_yylhs = [
            0,    1,    2,    2,    3,    3,    4,    4,    5,    5,
            6
    ];

    private $_yylen = [
            1,    1,    1,    3,    1,    3,    1,    2,    1,    3,
            1
    ];

    public static function create()
    {
        return new PatternParser();
    }

    public function parse($string)
    {
        $this->_inputLines = explode("\n", $string);

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
                         $yyval = new Wildcard(); 
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
        do {
            $this->_lexbuf = preg_replace('/^[\t ]+/', '', $this->_lexbuf);
            if ($this->_lexbuf) {
                break;
            }
        } while ($this->_lexbuf = $this->_readLine());

        $this->_lexbuf = str_replace(PHP_EOL, "\n", $this->_lexbuf);

        return $this->_readToken();
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

    private function _readToken()
    {
        $ret = ord($this->_lexbuf);
        $this->_lexbuf = substr($this->_lexbuf, 1);

        return $ret;
    }
}
