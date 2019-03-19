<?php

namespace Phinder\Parser\PatternParser;

use PhpParser\ErrorHandler;

class Lexer extends \PhpParser\Lexer\Emulative
{
    public function startLexing(string $code, ErrorHandler $errorHandler = null)
    {
        parent::startLexing($code, $errorHandler);
        for ($i = 0; $i < count($this->tokens); ++$i) {
            if (is_array($this->tokens[$i])) {
                $type = $this->tokens[$i][0];
                $str = $this->tokens[$i][1];
                if ($type === 319 && $str === '_') {
                    $this->tokens[$i] = '?';
                }
            }
        }
    }
}
