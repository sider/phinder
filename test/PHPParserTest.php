<?php

use PHPUnit\Framework\TestCase;
use Phinder\Error\InvalidPHP;
use Phinder\Parser\PHPParser;

class PHPParserTest extends TestCase
{
    private $_parser;

    public function setUp()
    {
        $this->_parser = new PHPParser();
    }

    public function testStr()
    {
        $xml = $this->_parser->parseStr('<?php echo 1; echo 2;');
        $res = $xml->xpath('//*[@class="Stmt_Echo"]');
        $this->assertSame(count($res), 2);
    }

    public function testInvalidStr()
    {
        $this->expectException(InvalidPHP::class);
        $this->_parser->parseStr('<?php if {');
    }
}
