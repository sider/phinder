<?php


class InitTest extends CliTest
{
    protected static $commandName = 'init';

    private static $_customYamlName = 'custom-name.yml';

    public function setUp()
    {
        parent::setUp();
        chdir('test');
    }

    public function tearDown()
    {
        if (file_exists('phinder.yml')) {
            unlink('phinder.yml');
        }
        if (file_exists(self::$_customYamlName)) {
            unlink(self::$_customYamlName);
        }
        chdir('..');
        parent::tearDown();
    }

    public function testInit()
    {
        $this->exec();
        $this->assertTrue(file_exists('phinder.yml'));
        $this->assertSame($this->getStatusCode(), 0);
    }

    public function testInitTwice()
    {
        $this->exec();
        $this->assertTrue(file_exists('phinder.yml'));
        $this->assertSame($this->getStatusCode(), 0);

        $this->exec();
        $this->assertTrue(file_exists('phinder.yml'));
        $this->assertSame($this->getStatusCode(), 1);
    }

    public function testInitCustomName()
    {
        $this->exec(['--config' => self::$_customYamlName]);
        $this->assertTrue(file_exists(self::$_customYamlName));
        $this->assertSame($this->getStatusCode(), 0);
    }
}
