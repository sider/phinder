<?php

namespace Phinder\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Phinder\API;

class FindCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('find')
            ->setDescription('Find pattern(s)')
            ->setDefinition(
                new InputDefinition(
                    [
                        new InputArgument(
                            'path',
                            InputArgument::OPTIONAL,
                            'Path to the target file/dir',
                            '.'
                        ),
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
        $config = $input->getOption('config');
        $phpPath = $input->getArgument('path');
        $jsonOutput = $input->getOption('format') === 'json';
        $violationCount = 0;
        $errorCount = 0;

        $generator = API::phind($config, $phpPath);
        while (true) {
            try {
                if (!$generator->valid()) {
                    break;
                }
                $match = $generator->current();
                $generator->next();

                ++$violationCount;

                $path = (string) $match->path;
                $id = $match->rule->id;
                $message = $match->rule->message;
                $startLine = (int) $match->xml['startLine'];
                $startFilePos = (int) $match->xml['startFilePosition'];
                $endLine = (int) $match->xml['endLine'];
                $endFilePos = (int) $match->xml['endFilePosition'];

                $code = @file_get_contents(
                    $match->path,
                    null,
                    null,
                    $startFilePos,
                    $endFilePos - $startFilePos + 1
                );
                $code = str_replace("\n", '\n', $code);

                // Start position
                $lines = explode(
                    "\n",
                    @file_get_contents($match->path, null, null, 0, $startFilePos)
                );
                $startPos = strlen($lines[count($lines) - 1]) + 1;

                // End position
                $lines = explode(
                    "\n",
                    @file_get_contents($match->path, null, null, 0, $endFilePos + 1)
                );
                $endPos = strlen($lines[count($lines) - 1]) + 1;

                if ($jsonOutput) {
                    $obj = [
                        'path' => $path,
                        'rule' => [
                            'id' => $id,
                            'message' => $message,
                        ],
                        'location' => [
                            'start' => [$startLine, $startPos],
                            'end' => [$endLine, $endPos],
                        ],
                    ];

                    if (count($match->rule->justifications)) {
                        $obj['justifications'] = $match->rule->justifications;
                    }

                    $outputBuffer['result'][] = $obj;
                } else {
                    $m = trim(str_replace(["\n", "\r"], ' ', $message));
                    echo "$path:$startLine:$startPos\t\033[31m$code\033[0m\t";
                    echo ($id === '') ? "\n" : "$m ($id)\n";
                }
            } catch (FileNotFound $e) {
                ++$errorCount;

                $msg = "File not found: {$e->path}";
                if ($jsonOutput) {
                    $outputBuffer['errors'][] = [
                        'type' => 'FileNotFound',
                        'message' => $msg,
                    ];
                } else {
                    fwrite(STDERR, "$msg\n");

                    return 1;
                }
            } catch (InvalidPattern $e) {
                ++$errorCount;

                $msg = 'Invalid pattern found';
                $msg += " in {$e->id} in {$e->path}: {$e->pattern}";
                if ($jsonOutput) {
                    $outputBuffer['errors'][] = [
                        'type' => 'InvalidPattern',
                        'message' => $msg,
                    ];
                } else {
                    fwrite(STDERR, "$msg\n");

                    return 1;
                }
            } catch (InvalidRule $e) {
                ++$errorCount;

                $sufs = ['st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
                $ord = "{$e->index}{$sufs[$e->index % 10 - 1]}";
                $msg = "Invalid {$e->key} value found in {$ord} rule in {$e->path}";

                if ($jsonOutput) {
                    $outputBuffer['errors'][] = [
                        'type' => 'InvalidRule',
                        'message' => $msg,
                    ];
                } else {
                    fwrite(STDERR, "$msg\n");

                    return 1;
                }
            } catch (InvalidYaml $e) {
                ++$errorCount;

                $msg = "Invalid yml file: {$e->path}";

                if ($jsonOutput) {
                    $outputBuffer['errors'][] = [
                        'type' => 'InvalidYaml',
                        'message' => $msg,
                    ];
                } else {
                    fwrite(STDERR, "$msg\n");

                    return 1;
                }
            } catch (InvalidPHP $e) {
                ++$errorCount;

                $msg = "PHP parse error in {$e->path}: {$e->error->getRawMessage()}";

                if ($jsonOutput) {
                    $outputBuffer['errors'][] = [
                        'type' => 'InvalidPHP',
                        'message' => $msg,
                    ];
                } else {
                    fwrite(STDERR, "\033[31m$msg\033[0m\n");
                }
            }
        }

        if ($jsonOutput) {
            echo json_encode($outputBuffer, JSON_UNESCAPED_SLASHES);
        }

        if ($errorCount !== 0 || $violationCount !== 0) {
            return 1;
        }

        return 0;
    }
}
