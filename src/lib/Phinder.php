<?php

namespace Phinder;

use Phinder\Parser\{PHPParser,RuleParser};


function phind($rulePath, $phpPath) {
    $phpParser = new PHPParser;
    $ruleParser = new RuleParser;

    $rules = \iterator_to_array($ruleParser->parse($rulePath));

    foreach ($phpParser->parse($phpPath) as $xml) {
        foreach ($rules as $rule) {
            foreach ($xml->xpath($rule->xpath) as $match) {
                yield new Match($xml['path'], getActualElement($match), $rule);
            }
        }
    }
}

function getActualElement($xml) {
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
