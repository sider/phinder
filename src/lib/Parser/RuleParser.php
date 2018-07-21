<?php

namespace Phinder\Parser;

use Symfony\Component\Yaml\Yaml;
use Phinder\Error\{InvalidRule,InvalidYaml};
use Phinder\Rule;
use function Funct\Strings\endsWith;


final class RuleParser extends FileParser {

    private $patternParser;

    public function __construct() {
        $this->patternParser = new PatternParser;
    }

    protected function support($path) {
        return endsWith($path, '.yml');
    }

    protected function parseFile($path) {
        $code = $this->getContent($path);
        $rules = Yaml::parse($code);
        if (!\is_array($rules)) {
            throw new InvalidYaml;
        }

        foreach ($rules as $arr) {
            foreach (static::parseArray($arr) as $r) {
                yield $r;
            }
        }
    }

    private function parseArray($arr) {
        $id = $arr['id'];
        $pattern = $arr['pattern'];
        $message = $arr['message'];

        if (!\is_string($id)) {
            throw new InvalidRule;
        }

        if (!\is_string($message)) {
            throw new InvalidRule;
        }

        if (!\is_string($pattern) && !\is_array($pattern)) {
            throw new InvalidRule;
        }

        $arr = \is_array($pattern)? $pattern : [$pattern];
        $xpath = $this->patternParser->parse($arr);

        foreach ($this->patternParser->parse($arr) as $xpath) {
            yield new Rule($id, $xpath, $message);
        }
    }

}
