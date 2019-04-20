<?php

namespace Phinder\Config;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Phinder\Error\FileNotFound;
use Phinder\Error\InvalidPattern;
use Phinder\Error\InvalidRule;
use Phinder\Error\InvalidYaml;
use Phinder\Pattern\Parser as PatternParser;

final class Parser
{
    private $_patternParser;

    public function __construct()
    {
        $this->_patternParser = new PatternParser();
    }

    public function parse($path)
    {
        return iterator_to_array($this->_parse($path));
    }

    private function _parse($path)
    {
        $content = @file_get_contents($path);
        if ($content === false) {
            throw new FileNotFound($path);
        }

        try {
            $rules = Yaml::parse($content);
        } catch (ParseException $e) {
            throw new InvalidYaml($path);
        }

        if (!\is_array($rules)) {
            throw new InvalidYaml($path);
        }

        if (\array_key_exists('rule', $rules)) {
            $rules = $rules['rule'];
        }

        for ($i = 0; $i < \count($rules); ++$i) {
            $arr = $rules[$i];

            try {
                yield static::_parseArray($arr, $i, $path);
            } catch (InvalidPattern $e) {
                $e->path = $path;
                throw $e;
            } catch (InvalidRule $e) {
                $e->path = $path;
                $e->index = $i + 1;
                throw $e;
            }
        }
    }

    private function _parseArray($arr)
    {
        $id = $arr['id'] ?? null;
        $pattern = $arr['pattern'] ?? null;
        $message = $arr['message'] ?? null;

        $justification = $arr['justification'] ?? [];

        $test = $arr['test'] ?? [];
        $pass = $test['pass'] ?? [];
        $fail = $test['fail'] ?? [];

        if (!\is_string($id)) {
            throw new InvalidRule('id');
        }

        if (!\is_string($message)) {
            throw new InvalidRule('message');
        }

        if (!\is_string($pattern) && !\is_array($pattern)) {
            throw new InvalidRule('pattern');
        }

        if (!\is_string($justification) && !\is_array($justification)) {
            throw new InvalidRule('justification');
        }

        if (!\is_string($pass) && !\is_array($pass)) {
            throw new InvalidRule('test.pass');
        }

        if (!\is_string($fail) && !\is_array($fail)) {
            throw new InvalidRule('test.fail');
        }

        $pats = \is_array($pattern) ? $pattern : [$pattern];
        $jsts = \is_array($justification) ? $justification : [$justification];
        $ppats = \is_array($pass) ? $pass : [$pass];
        $fpats = \is_array($fail) ? $fail : [$fail];

        try {
            $expr = implode(' ||| ', $pats);
            $pattern = $this->_patternParser->parse($expr);

            return new Rule($id, $pattern, $message, $jsts, $ppats, $fpats);
        } catch (InvalidPattern $e) {
            $e->id = $id;
            throw $e;
        }
    }
}
