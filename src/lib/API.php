<?php

namespace Phinder;

use Phinder\Parser\{PHPParser,RuleParser};

class API
{
    public static function phind($rulePath, $phpPath)
    {
        $phpParser = new PHPParser;
        $rules = static::parseRule($rulePath);
        foreach ($phpParser->parse($phpPath) as $xml) {
            foreach ($rules as $rule) {
                foreach ($xml->xpath($rule->xpath) as $match) {
                    $elem = static::getActualElement($match);
                    yield new Match($xml['path'], $elem, $rule);
                }
            }
        }
    }

    public static function parseRule($path)
    {
        $ruleParser = new RuleParser;
        return \iterator_to_array($ruleParser->parse($path));
    }

    private static function getActualElement($xml)
    {
        if ($xml['startLine'] === null) {
            foreach ($xml->children() as $child) {
                $elem = getActualElement($child);
                if ($elem !== null) {
                    return $elem;
                }
            }
            return null;
        } else {
            return $xml;
        }
    }
}
