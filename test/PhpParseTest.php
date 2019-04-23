<?php

use PHPUnit\Framework\TestCase;
use Phinder\Php\Parser;

class PhpParseTest extends TestCase
{
    private $_parser;

    public function setUp()
    {
        $this->_parser = new Parser();
    }

    public function testParseSuccess()
    {
        $ast = $this->_parser->parseFile(__FILE__);
        $this->assertNotSame($ast, null);
    }

    /**
     * @expectedException \Phinder\Error\FileNotFound
     */
    public function testParseFail1()
    {
        $this->_parser->parseFile('non-existent.php');
    }

    /**
     * @expectedException \Phinder\Error\FileNotFound
     */
    public function testParseFail2()
    {
        foreach ($this->_parser->parseFilesInDirectory('non-existent') as $ast) {
        }
    }
}
