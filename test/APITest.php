<?php

use PHPUnit\Framework\TestCase;
use Phinder\API;


class APITest extends TestCase
{

    function testParseRule()
    {
        $rs = API::parseRule('./test/res/yml/dir');
        $this->assertSame(count($rs), 2);
    }

    function testPhind()
    {
        $i = 0;
        foreach (API::phind('./test/res/yml/dir', './test/res/php/valid') as $m) {
            $i++;
        }
        $this->assertEquals($i, 8);
    }
}
