<?php

namespace Phinder\Parser;

use Phinder\Error\InvalidPattern;
use Phinder\Parser\PatternParser\ParserFactory;
use Phinder\{Wildcard, WildcardN};
use PhpParser\Error;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayItem;


final class PatternParser
{

    private $_patternParser = null;

    public function __construct()
    {
        $this->patternParser = (new ParserFactory)->create(
            ParserFactory::PREFER_PHP7
        );
    }

    public function parse($arr)
    {
        foreach ($arr as $p) {
            try {
                $ast = $this->patternParser->parse("<?php $p");
                yield '//*' . static::_buildXPath($ast);
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
                $cnt = 0;
                $vlen = false;

                $xp = '';
                foreach ($ast as $i => $a) {
                    $xp .= "/item$i" . static::_buildXPath($a) . "/..";

                    if ($a instanceof Arg) {
                        if ($a->value instanceof WildcardN) {
                            $vlen = true;
                        } else {
                            $cnt++;
                        }
                    } else if ($a instanceof ArrayItem) {
                        if ($a->value instanceof WildcardN) {
                            $vlen = true;
                        } else {
                            $cnt++;
                        }
                    } else {
                        $cnt++;
                    }

                }

                return ($vlen? "[count(*)>=$cnt]" : "[count(*)=$cnt]") . $xp;

            } else {
                return "[count(*)=0]";
            }

        } else if ($ast instanceof Wildcard) {
            return '';

        } else if (\is_subclass_of($ast, '\PhpParser\NodeAbstract')) {
            $t = $ast->getType();

            $xp = null;
            foreach ($ast->getSubNodeNames() as $n) {
                $x = static::_buildXPath($ast->$n);
                $xp .= "/$n$x/..";
            }
            return $xp;

        } else {
            return "[.='$ast']";
        }
    }

}
