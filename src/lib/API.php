<?php

namespace Phinder;

use Phinder\Parser\PHPParser;
use Phinder\Parser\RuleParser;

class API
{
    public static function phind($rulePath, $phpPath)
    {
        $phpParser = new PHPParser();
        $rules = static::parseRule($rulePath);
        foreach ($phpParser->parse($phpPath) as $xml) {
            foreach ($rules as $rule) {
                foreach ($xml->xpath($rule->xpath) as $match) {
                    yield new Match($xml['path'], $match, $rule);
                }
            }
        }
    }

    public static function parseRule($path)
    {
        $ruleParser = new RuleParser();
        $rules = [];
        foreach ($ruleParser->parse($path) as $r) {
            $rules[] = $r;
        }

        return $rules;
    }
}
