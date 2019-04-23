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
        $this->_printHeader();
        $this->_printHelp();

        $helper = $this->getHelper('question');
        $question = new Question('> ');
        while (true) {
            $input = trim(
                $helper->ask(
                    $this->getInput(),
                    $this->getOutput(),
                    $question
                )
            );

            if ($input === '') {
                continue;
            }

            if ($input === 'exit') {
                break;
            }

            if (preg_match('/^find\s+(.+)$/', $input, $pattern)) {
                try {
                    $pattern = $pattern[1];
                    $tmp = tmpfile();
                    $config = stream_get_meta_data($tmp)['uri'];
                    fwrite($tmp, "- id: ''\n  pattern: $pattern\n  message: ''");
                    $errorCode = $this->_runFind($config);
                } catch (\Exception $e) {
                    echo $e->getTraceAsString()."\n";
                }
            } else {
                echo "Unknown command: $input\n";
                $this->_printHelp();
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

    private function _printHeader()
    {
        echo "Phinder console\n";
    }

    private function _printHelp()
    {
        echo "\n";
        echo "Available commands:\n";
        echo "  - find <pattern>  Find <pattern>\n";
        echo "  - exit            Exit\n";
        echo "\n";
    }
}
