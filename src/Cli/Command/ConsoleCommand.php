<?php

namespace Phinder\Cli\Command;

use Phinder\Cli\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Question\Question;

class ConsoleCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('console')
            ->setDescription('Start interactive console')
            ->addArgument(...self::$pathArgDef)
            ->addOption(...self::$formatOptDef);
    }

    protected function main()
    {
        $helper = $this->getHelper('question');
        $question = new Question('pattern> ');
        while (true) {
            $pattern = trim(
                $helper->ask(
                    $this->getInput(),
                    $this->getOutput(),
                    $question
                )
            );
            if ($pattern !== '') {
                $tmp = tmpfile();
                $config = stream_get_meta_data($tmp)['uri'];
                fwrite($tmp, "- id: ''\n  pattern: $pattern\n  message: ''");
                $errorCode = $this->_runFind($config);
            }
        }
    }

    private function _runFind($path)
    {
        $command = $this->getApplication()->find('find');

        $input = new ArrayInput(
            [
                'command' => 'find',
                '--config' => $path,
                'path' => $this->getPath(),
            ]
        );

        return $command->run($input, $this->getOutput());
    }
}
