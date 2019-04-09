<?php

namespace Phinder\Cli\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Command extends SymfonyCommand
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
}
