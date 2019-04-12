<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Phinder\Cli\Main;

abstract class CliTest extends TestCase
{
    private $_tester;

    protected static $commandName = null;

    public function setUp()
    {
        $command = (new Main())->find(static::$commandName);
        $this->_tester = new CommandTester($command);
    }

    public function tearDown()
    {
        $this->_tester = null;
    }

    protected function exec($input = array(), $options = array())
    {
        $this->_tester->execute($input, $options);
    }

    protected function getDisplay()
    {
        return $this->_tester->getDisplay(true);
    }

    protected function getStatusCode()
    {
        return $this->_tester->getStatusCode();
    }

    protected function getDisplayJson()
    {
        return json_decode($this->getDisplay(), true);
    }
}
