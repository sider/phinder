<?php

use PHPUnit\Framework\TestCase;
use Phinder\Error\InvalidRule;
use Phinder\Error\InvalidPattern;
use Phinder\Error\InvalidYaml;
use Phinder\Parser\RuleParser;

class RuleParserTest extends TestCase
{
    private $_parser;

    public function setUp()
    {
        $this->_parser = new RuleParser();
    }

    public function testMin()
    {
        $r = $this->_parser->parse('./test/res/yml/min.yml')->current();
        $this->assertEquals($r->id, 'sample');
        $this->assertEquals($r->message, 'message');
    }

    public function testVarArgs()
    {
        $i = 1;
        foreach ($this->_parser->parse('./test/res/yml/misc.yml') as $r) {
            $this->assertEquals($r->id, "sample$i");
            $this->assertEquals($r->message, 'message');
            ++$i;
        }
    }

    public function testInvaldYaml1()
    {
        $this->expectException(InvalidYaml::class);
        $this->_parser->parse('./test/res/yml/invalid.yml')->current();
    }

    public function testInvalidYaml2()
    {
        $this->expectException(InvalidYaml::class);
        $this->_parser->parse(__FILE__)->current();
    }

    public function testInvalidPattern()
    {
        $this->expectException(InvalidPattern::class);
        $this->_parser->parse('./test/res/yml/invalid-pattern.yml')->current();
    }

    public function testNoPattern()
    {
        $this->expectException(InvalidRule::class);
        $this->_parser->parse('./test/res/yml/no-pattern.yml')->current();
    }

    public function testInvalidId()
    {
        $this->expectException(InvalidRule::class);
        $this->_parser->parse('./test/res/yml/invalid-id.yml')->current();
    }

    public function testNoId()
    {
        $this->expectException(InvalidRule::class);
        $this->_parser->parse('./test/res/yml/no-id.yml')->current();
    }

    public function testInvalidMessage()
    {
        $this->expectException(InvalidRule::class);
        $this->_parser->parse('./test/res/yml/invalid-message.yml')->current();
    }

    public function testNoMessage()
    {
        $this->expectException(InvalidRule::class);
        $this->_parser->parse('./test/res/yml/no-message.yml')->current();
    }

    public function testInvalidJustification()
    {
        $this->expectException(InvalidRule::class);
        $this->_parser->parse('./test/res/yml/invalid-justification.yml')->current();
    }

    public function testInvalidTestPass()
    {
        $this->expectException(InvalidRule::class);
        $this->_parser->parse('./test/res/yml/invalid-test-pass.yml')->current();
    }

    public function testInvalidTestFail()
    {
        $this->expectException(InvalidRule::class);
        $this->_parser->parse('./test/res/yml/invalid-test-fail.yml')->current();
    }
}
