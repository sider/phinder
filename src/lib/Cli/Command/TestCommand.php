<?php

namespace Phinder\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Phinder\API;
use Phinder\Parser\PHPParser;

class TestCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('test')
            ->setDescription('Test pattern(s)')
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
                        new InputOption(
                            'format',
                            'f',
                            InputOption::VALUE_REQUIRED,
                            'Output format',
                            'text'
                        ),
                    ]
                )
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $errorCount = 0;
        $config = $input->getOption('config');

        $phpParser = new PHPParser();
        foreach (API::parseRule($config) as $r) {
            foreach ($r->fail_patterns as $p) {
                $xml = $phpParser->parseStr("<?php $p;");
                if (0 === count($xml->xpath($r->xpath))) {
                    echo "`$p` does not match the rule {$r->id} but should match that rule.\n";
                    ++$errorCount;
                }
            }

            foreach ($r->pass_patterns as $p) {
                $xml = $phpParser->parseStr("<?php $p;");
                if (0 < count($xml->xpath($r->xpath))) {
                    echo "`$p` matches the rule {$r->id} but should not match that rule.\n";
                    ++$errorCount;
                }
            }
        }

        if ($errorCount === 0) {
            fwrite(STDERR, "No error\n");

            return 0;
        } else {
            return 1;
        }
    }
}
