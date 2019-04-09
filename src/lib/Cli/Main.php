<?php

namespace Phinder\Cli;

use Symfony\Component\Console\Application;
use Phinder\Cli\Command\ConsoleCommand;
use Phinder\Cli\Command\InitCommand;
use Phinder\Cli\Command\FindCommand;
use Phinder\Cli\Command\TestCommand;

class Main
{
    private static $_name = 'phinder';

    private static $_version = '0.8.0';

    private $_application;

    public function __construct()
    {
        $this->_application = new Application(self::$_name, self::$_version);
        $this->_configureCommands();
    }

    public function run()
    {
        $this->_application->run();
    }

    private function _configureCommands()
    {
        $this->_application->add(new ConsoleCommand());
        $this->_application->add(new InitCommand());
        $this->_application->add(new FindCommand());
        $this->_application->add(new TestCommand());
    }
}
