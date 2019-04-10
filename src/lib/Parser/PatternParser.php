<?php

namespace Phinder\Parser;

use Phinder\Error\InvalidPattern;
use Phinder\Parser\PatternParser\ParserFactory;
use Phinder\Wildcard;
use Phinder\WildcardN;
use Phinder\ArrayItem;
use PhpParser\Error;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ConstFetch;

final class PatternParser
{
    private $_patternParser = null;

    public function __construct()
    {
        $this->patternParser = (new ParserFactory())->create(
            ParserFactory::PREFER_PHP7
        );
    }

    public function parse($arr)
    {
        foreach ($arr as $p) {
            try {
                $ast = $this->patternParser->parse("<?php $p");
                $xpath = '//*'.static::_buildXPath($ast);
                yield $xpath;
            } catch (Error $e) {
                throw new InvalidPattern($p, $e);
            }
        }
    }

    private static function _buildXPath($ast)
    {
        if (\is_array($ast)) {
            $len = count($ast);
            if (0 < $len) {
                return static::_buildArrXPath($ast);
            } else {
                return '[count(*)=0]';
            }
        } elseif ($ast instanceof Wildcard) {
            return '';
        } elseif ($ast instanceof WildcardN) {
            return '';
        } elseif ($ast instanceof ConstFetch) {
            $xp = static::_buildXPath($ast->name);
            $prefix = "/*[local-name()='class' or local-name()='name']";

            return $prefix.$xp.'/..';
        } elseif (\is_subclass_of($ast, '\PhpParser\NodeAbstract')) {
            $t = $ast->getType();

            $xp = '';
            foreach ($ast->getSubNodeNames() as $n) {
                $x = static::_buildXPath($ast->$n);
                $xp .= "/$n$x/..";
            }

            return "[@class='$t']".$xp;
        } else {
            return "[.='$ast']";
        }
    }

    private static function _buildArrXPath($ast)
    {
        $cnt = 0;
        $vlen = static::_isVarLen($ast);
        $hasNegArrayItem = false;

        $xp = '';
        foreach ($ast as $i => $a) {
            if ($a instanceof Arg) {
                $xp .= "/item$i".static::_buildXPath($a).'/..';
                if (!($a->value instanceof WildcardN)) {
                    ++$cnt;
                }
            } elseif (is_string($a)) {
                $xp .= "/item$i".static::_buildXPath($a).'/..';
                ++$cnt;
            } elseif ($a instanceof ArrayItem) {
                if (!($a->value instanceof WildcardN)) {
                    $head = $vlen ? '*' : "item$i";
                    if ($a->negation) {
                        $xp .= "/${head}[not(../${head}"
                            .static::_buildXPath($a)
                            .')]/..';
                        $hasNegArrayItem = true;
                    } else {
                        $xp .= "/$head".static::_buildXPath($a).'/..';
                        ++$cnt;
                    }
                }
            } else {
                // @codeCoverageIgnoreStart
                throw new Exception();
                // @codeCoverageIgnoreEnd
            }
        }

        if ($cnt == 0) {
            if ($hasNegArrayItem) {
                return '[count(*)>=0]'.$xp;
            } else {
                return '[count(*)>=0]';
            }
        } else {
            return ($vlen ? "[count(*)>=$cnt]" : "[count(*)=$cnt]").$xp;
        }
    }

    private static function _isVarLen($ast)
    {
        foreach ($ast as $a) {
            if ($a instanceof ArrayItem || $a instanceof Arg) {
                if ($a->value instanceof WildcardN) {
                    return true;
                }
            }
        }

        return false;
    }
}
