<?php

namespace Phinder\PatternParser;

class StringReader
{
    private $_lines;

    public function __construct($string)
    {
        $this->_lines = explode("\n", $string);
    }

    public function readLine()
    {
        $line = array_shift($this->_lines);

        return $line === null ? false : $line;
    }
}
