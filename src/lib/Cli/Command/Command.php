<?php

namespace Phinder\Cli\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SymfonyCommand
{
    protected static $pathArgDef = [
        'path',
        InputArgument::OPTIONAL,
        'Path to the target file/dir',
        '.',
    ];

    protected static $configOptDef = [
        'config',
        'c',
        InputOption::VALUE_REQUIRED,
        'Path to configration file',
        'phinder.yml',
    ];

    protected static $formatOptDef = [
        'format',
        'f',
        InputOption::VALUE_REQUIRED,
        'Output format',
        'text',
    ];

    abstract protected function main();

    private $_input;

    private $_output;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_input = $input;
        $this->_output = $output;

        return $this->main();
    }

    protected function getPath()
    {
        return $this->_input->getArgument(self::$pathArgDef[0]);
    }

    protected function getConfig()
    {
        return $this->_input->getOption(self::$configOptDef[0]);
    }

    protected function getFormat()
    {
        return $this->_input->getOption(self::$formatOptDef[0]);
    }

    protected function getInput()
    {
        return $this->_input;
    }

    protected function getOutput()
    {
        return $this->_output;
    }

    protected function getErrorOutput()
    {
        if ($this->_output instanceof ConsoleOutputInterface) {
            return $this->_output->getErrorOutput();
        } else {
            return $this->_output;
        }
    }
}
