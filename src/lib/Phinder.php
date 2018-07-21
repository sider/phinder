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
                $line = getLine($match);
                yield new Match($xml['path'], $line, $rule);
            }
        }
    }
}

function getLine($xml) {
    if ($xml['start'] === null) {
        foreach ($xml->children() as $e) {
            $line = getLine($e);
            if ($line !== null) {
                return $line;
            }
        }
        return null;
    } else {
        return $xml['start'];
    }
}
