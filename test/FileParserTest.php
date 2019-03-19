<?php

use PHPUnit\Framework\TestCase;
use Phinder\Error\FileNotFound;
use Phinder\Error\InvalidPHP;
use Phinder\Parser\PHPParser;
use Phinder\Parser\RuleParser;

class FileParserTest extends TestCase
{
    private $_phpParser;

    private $_ruleParser;

    public function setUp()
    {
        $this->_phpParser = new PHPParser();
        $this->_ruleParser = new RuleParser();
    }

    public function testValidPHPDir()
    {
        foreach ($this->_phpParser->parse('./test/res/php/valid') as $xml) {
            $res = $xml->xpath('//*[@class="Stmt_Echo"]');
            $this->assertSame(count($res), 1);
        }
    }

    public function testInvalidPHPDir()
    {
        $this->expectException(InvalidPHP::class);
        foreach ($this->_phpParser->parse('./test/res/php/invalid') as $xml) {
        }
    }

    public function testValidRuleDir()
    {
        $i = 0;
        foreach ($this->_ruleParser->parse('./test/res/yml/dir') as $r) {
            ++$i;
        }
        $this->assertSame($i, 2);
    }

    public function testNonExistent()
    {
        $this->expectException(FileNotFound::class);
        $this->_phpParser->parse('./test/res/nonexistent.php')->current();
    }
}
