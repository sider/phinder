<?php

use PHPUnit\Framework\TestCase;
use Phinder\Pattern\Parser as PatternParser;
use Phinder\PhpParser;

class PatternMatchTest extends TestCase
{
    private $_patternParser;

    private $_phpParser;

    public function setUp()
    {
        $this->_patternParser = new PatternParser();
        $this->_phpParser = new PhpParser();
    }
}
