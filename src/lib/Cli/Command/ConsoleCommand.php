<?php

namespace Phinder\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ConsoleCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('console')
            ->setDescription('Start interactive console')
            ->setDefinition(
                new InputDefinition(
                    [
                        new InputArgument(
                            'path',
                            InputArgument::OPTIONAL,
                            'Path to the target file/dir',
                            '.'
                        ),
                    ]
                )
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('pattern> ');
        while (true) {
            $pattern = trim($helper->ask($input, $output, $question));
            if ($pattern !== '') {
                $tmp = tmpfile();
                $config = stream_get_meta_data($tmp)['uri'];
                fwrite($tmp, "- id: ''\n  pattern: $pattern\n  message: ''");
                $errorCode = $this->_runFind($config, $input, $output);
            }
        }
    }

    private function _runFind($path, $input, $output)
    {
        $command = $this->getApplication()->find('find');
        $arguments = [
            'command' => 'find',
            '--config' => $path,
            'path' => $input->getArgument('path'),
        ];
        $input = new ArrayInput($arguments);

        return $command->run($input, $output);
    }
}
