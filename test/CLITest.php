<?php

use PHPUnit\Framework\TestCase;
use Phinder\API;


class CLITest extends TestCase
{
    function testInit()
    {
        $work_dir = getcwd();
        try {
            chdir(__DIR__ . "/res");

            $this->assertFileNotExists("phinder.yml");
            exec(__DIR__ . "/../bin/phinder init");
            $this->assertFileExists("phinder.yml");

            $expected = file_get_contents(__DIR__ . "/../sample/phinder.yml");
            $got = file_get_contents("phinder.yml");
            $this->assertSame($expected, $got);
        } finally {
            if (file_exists("phinder.yml")) {
                unlink("phinder.yml");
            }
            chdir($work_dir);
        }
    }

    function testInitWhenConfigAlreadyExists()
    {
        $work_dir = getcwd();
        try {
            chdir(__DIR__ . "/res");
            touch("phinder.yml");

            $this->assertFileExists("phinder.yml");
            exec(__DIR__ . "/../bin/phinder init");
            $this->assertFileExists("phinder.yml");

            $expected = file_get_contents(__DIR__ . "/../sample/phinder.yml");
            $got = file_get_contents("phinder.yml");
            $this->assertNotSame($expected, $got);
        } finally {
            if (file_exists("phinder.yml")) {
                unlink("phinder.yml");
            }
            chdir($work_dir);
        }
    }
}
