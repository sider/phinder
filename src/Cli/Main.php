<?php

namespace Phinder\Cli;

use Symfony\Component\Console\Application;

class Main
{
    private static $_name = 'phinder';

    private static $_version = '0.8.1';

    private static $_commands = [
        'Command\ConsoleCommand',
        'Command\InitCommand',
        'Command\FindCommand',
        'Command\TestCommand',
    ];

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

    public function find($name)
    {
        return $this->_application->find($name);
    }

    private function _configureCommands()
    {
        foreach (self::$_commands as $command) {
            $class = __NAMESPACE__.'\\'.$command;
            $this->_application->add(new $class());
        }
    }
}
