<?php


/* Prototype file of PHP parser.
 * Written by Masato Bito
 * This file is PUBLIC DOMAIN.
 */

$buffer = null;
$token = null;
$toktype = null;

define('YYERRTOK', 256);


/*
  #define yyclearin (yychar = -1)
  #define yyerrok (yyerrflag = 0)
  #define YYRECOVERING (yyerrflag != 0)
  #define YYERROR  goto yyerrlab
*/


/** Debug mode flag **/
$yydebug = false;

/** lexical element object **/
$yylval = null;

function yyprintln($msg)
{
  echo "$msg\n";
}

function yyflush()
{
  return;
}



$yytranslate = array(
      0,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    2,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    3,    3,    3,    3,
      3,    3,    3,    3,    3,    3,    1
  );

define('YYBADCH', 3);
define('YYMAXLEX', 257);
define('YYTERMS', 3);
define('YYNONTERMS', 2);

$yyaction = array(
      3,    0
  );

define('YYLAST', 2);

$yycheck = array(
      2,    0
  );

$yybase = array(
     -2,    1
  );

define('YY2TBLSTATE', 0);

$yydefault = array(
  32767,32767
  );



$yygoto = array(
  );

define('YYGLAST', 0);

$yygcheck = array(
  );

$yygbase = array(
      0,    0
  );

$yygdefault = array(
  -32768,    1
  );

$yylhs = array(
      0,    1
  );

$yylen = array(
      1,    1
  );

define('YYSTATES', 3);
define('YYNLSTATES', 2);
define('YYINTERRTOK', 1);
define('YYUNEXPECTED', 32767);
define('YYDEFAULT', -32766);

/*
 * Parser entry point
 */

function yyparse()
{
  global $buffer, $token, $toktype, $yyaction, $yybase, $yycheck, $yydebug,
    $yydebug, $yydefault, $yygbase, $yygcheck, $yygdefault, $yygoto, $yylen,
    $yylhs, $yylval, $yyproduction, $yyterminals, $yytranslate;

  $yyastk = array();
  $yysstk = array();

  $yyn = $yyl = 0;
  $yystate = 0;
  $yychar = -1;

  $yysp = 0;
  $yysstk[$yysp] = 0;
  $yyerrflag = 0;
  while (true) {
    if ($yybase[$yystate] == 0)
      $yyn = $yydefault[$yystate];
    else {
      if ($yychar < 0) {
        if (($yychar = yylex()) <= 0) $yychar = 0;
        $yychar = $yychar < YYMAXLEX ? $yytranslate[$yychar] : YYBADCH;
      }

      if ((($yyn = $yybase[$yystate] + $yychar) >= 0
	   && $yyn < YYLAST && $yycheck[$yyn] == $yychar
           || ($yystate < YY2TBLSTATE
               && ($yyn = $yybase[$yystate + YYNLSTATES] + $yychar) >= 0
               && $yyn < YYLAST && $yycheck[$yyn] == $yychar))
	  && ($yyn = $yyaction[$yyn]) != YYDEFAULT) {
        /*
         * >= YYNLSTATE: shift and reduce
         * > 0: shift
         * = 0: accept
         * < 0: reduce
         * = -YYUNEXPECTED: error
         */
        if ($yyn > 0) {
          /* shift */
          $yysp++;

          $yysstk[$yysp] = $yystate = $yyn;
          $yyastk[$yysp] = $yylval;
          $yychar = -1;

          if ($yyerrflag > 0)
            $yyerrflag--;
          if ($yyn < YYNLSTATES)
            continue;

          /* $yyn >= YYNLSTATES means shift-and-reduce */
          $yyn -= YYNLSTATES;
        } else
          $yyn = -$yyn;
      } else
        $yyn = $yydefault[$yystate];
    }

    while (true) {
      /* reduce/error */
      if ($yyn == 0) {
        /* accept */
        yyflush();
        return 0;
      }
      else if ($yyn != YYUNEXPECTED) {
        /* reduce */
        $yyl = $yylen[$yyn];
        $n = $yysp-$yyl+1;
        $yyval = isset($yyastk[$n]) ? $yyastk[$n] : null;
        /* Following line will be replaced by reduce actions */
        switch($yyn) {
        case 1:
{} break;
        }
        /* Goto - shift nonterminal */
        $yysp -= $yyl;
        $yyn = $yylhs[$yyn];
        if (($yyp = $yygbase[$yyn] + $yysstk[$yysp]) >= 0 && $yyp < YYGLAST
            && $yygcheck[$yyp] == $yyn)
          $yystate = $yygoto[$yyp];
        else
          $yystate = $yygdefault[$yyn];

        $yysp++;

        $yysstk[$yysp] = $yystate;
        $yyastk[$yysp] = $yyval;
      }
      else {
        /* error */
        switch ($yyerrflag) {
        case 0:
          yyerror("syntax error");
        case 1:
        case 2:
          $yyerrflag = 3;
          /* Pop until error-expecting state uncovered */

          while (!(($yyn = $yybase[$yystate] + YYINTERRTOK) >= 0
                   && $yyn < YYLAST && $yycheck[$yyn] == YYINTERRTOK
                   || ($yystate < YY2TBLSTATE
                       && ($yyn = $yybase[$yystate + YYNLSTATES] + YYINTERRTOK) >= 0
                       && $yyn < YYLAST && $yycheck[$yyn] == YYINTERRTOK))) {
            if ($yysp <= 0) {
              yyflush();
              return 1;
            }
            $yystate = $yysstk[--$yysp];
          }
          $yyn = $yyaction[$yyn];
          $yysstk[++$yysp] = $yystate = $yyn;
          break;

        case 3:
          if ($yychar == 0) {
            yyflush();
            return 1;
          }
          $yychar = -1;
          break;
        }
      }

      if ($yystate < YYNLSTATES)
        break;
      /* >= YYNLSTATES means shift-and-reduce */
      $yyn = $yystate - YYNLSTATES;
    }
  }
}

$lexbuf = '';

function yylex()
{
    global $lexbuf, $yylval, $yyinput;
    do {
        $lexbuf = preg_replace('/^[\t ]+/', '', $lexbuf);
        if ($lexbuf) break;
    } while ($lexbuf = $yyinput->readLine());
    $lexbuf = str_replace(PHP_EOL, "\n", $lexbuf);

    if (preg_match('/^(\d+)/', $lexbuf, $matches)) {
        $yylval = (int)$matches[1];
        $lexbuf = substr($lexbuf, strlen($matches[1]));
        return NUMBER;
    } else {
        $ret = ord($lexbuf);
        $lexbuf = substr($lexbuf, 1);
        return $ret;
    }
}

function yyerror($msg)
{
}

$yyinput = null;

function yyinput($string)
{
    global $yyinput;
    $yyinput = new StringReader($string);
}

