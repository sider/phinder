<?php

namespace Phinder\Cli;

use Phinder\Php\Parser as PhpParser;
use Phinder\RuleParser;
use Phinder\Match;

class API
{
    public static function phind($rulePath, $phpPath)
    {
        $phpParser = new PhpParser();
        $rules = static::parseRule($rulePath);
        foreach ($phpParser->parseFilesInDirectory($phpPath) as $phpFile) {
            foreach ($rules as $rule) {
                foreach ($rule->pattern->visit($phpFile->ast) as $match) {
                    yield new Match($phpFile->path, $match, $rule);
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
