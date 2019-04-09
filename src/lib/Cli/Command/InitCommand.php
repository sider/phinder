<?php

namespace Phinder\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Create sample phinder.yml')
            ->setDefinition(
                new InputDefinition(
                    [
                        new InputOption(
                            'config',
                            'c',
                            InputOption::VALUE_REQUIRED,
                            'Path to configration file',
                            'phinder.yml'
                        ),
                    ]
                )
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $input->getOption('config');

        if (file_exists($config)) {
            $output->getErrorOutput()->writeln(
                "Cannot generate $config: file already exists"
            );

            return 1;
        }

        if (!copy(__DIR__.'/../../../../sample/phinder.yml', $config)) {
            $output->getErrorOutput()->writeln(
                "Cannot generate $config: failed to copy"
            );

            return 1;
        }

        $output->writeln("`$config` has been created successfully");

        return 0;
    }
}
