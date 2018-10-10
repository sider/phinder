<?php

use PHPUnit\Framework\TestCase;
use Phinder\Error\{InvalidRule, InvalidPattern, InvalidYaml};
use Phinder\Parser\RuleParser;


class RuleParserTest extends TestCase {

    private $parser;

    function setUp() {
        $this->parser = new RuleParser;
    }

    function testMin() {
        $r = $this->parser->parse('./test/res/yml/min.yml')->current();
        $this->assertEquals($r->id, "sample");
        $this->assertEquals($r->message, "message");
    }

    function testVarArgs() {
        $i=1;
        foreach ($this->parser->parse('./test/res/yml/misc.yml') as $r) {
            $this->assertEquals($r->id, "sample$i");
            $this->assertEquals($r->message, "message");
            $i++;
        }
    }

    function testInvaldYaml1() {
        $this->expectException(InvalidYaml::class);
        $this->parser->parse('./test/res/yml/invalid.yml')->current();
    }

    function testInvalidYaml2() {
        $this->expectException(InvalidYaml::class);
        $this->parser->parse(__FILE__)->current();
    }

    function testInvalidPattern() {
        $this->expectException(InvalidPattern::class);
        $this->parser->parse('./test/res/yml/invalid-pattern.yml')->current();
    }

    function testNoPattern() {
        $this->expectException(InvalidRule::class);
        $this->parser->parse('./test/res/yml/no-pattern.yml')->current();
    }

    function testInvalidId() {
        $this->expectException(InvalidRule::class);
        $this->parser->parse('./test/res/yml/invalid-id.yml')->current();
    }

    function testNoId() {
        $this->expectException(InvalidRule::class);
        $this->parser->parse('./test/res/yml/no-id.yml')->current();
    }

    function testInvalidMessage() {
        $this->expectException(InvalidRule::class);
        $this->parser->parse('./test/res/yml/invalid-message.yml')->current();
    }

    function testNoMessage() {
        $this->expectException(InvalidRule::class);
        $this->parser->parse('./test/res/yml/no-message.yml')->current();
    }

    function testInvalidJustification() {
        $this->expectException(InvalidRule::class);
        $this->parser->parse('./test/res/yml/invalid-justification.yml')->current();
    }

    function testInvalidTestPass() {
        $this->expectException(InvalidRule::class);
        $this->parser->parse('./test/res/yml/invalid-test-pass.yml')->current();
    }

    function testInvalidTestFail() {
        $this->expectException(InvalidRule::class);
        $this->parser->parse('./test/res/yml/invalid-test-fail.yml')->current();
    }
}
