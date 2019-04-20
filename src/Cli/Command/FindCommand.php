<?php

namespace Phinder\Cli\Command;

use Phinder\Cli\Command;
use Phinder\Config\Parser as ConfigParser;
use Phinder\Php\Parser as PhpParser;
use Phinder\Pattern\Match;

class FindCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('find')
            ->setDescription('Find pattern(s)')
            ->addArgument(...self::$pathArgDef)
            ->addOption(...self::$configOptDef)
            ->addOption(...self::$formatOptDef);
    }

    protected function main()
    {
        $config = $this->getConfig();
        $phpPath = $this->getPath();
        $jsonOutput = $this->getFormat() === 'json';
        $outputBuffer = ['result' => [], 'errors' => []];
        $violationCount = 0;
        $errorCount = 0;

        $generator = $this->_run($config, $phpPath);
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
                $startLine = (int) $match->phpNode->getStartLine();
                $startFilePos = (int) $match->phpNode->getStartFilePos();
                $endLine = (int) $match->phpNode->getEndLine();
                $endFilePos = (int) $match->phpNode->getEndFilePos();

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
                    $msg = "$path:$startLine:$startPos\t\033[31m$code\033[0m\t";
                    $msg .= ($id === '') ? '' : "$m ($id)";
                    $this->getOutput()->writeln($msg);
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
                    $this->getErrorOutput()->writeln($msg);

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
                    $this->getErrorOutput()->writeln($msg);

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
                    $this->getErrorOutput()->writeln($msg);

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
                    $this->getErrorOutput()->writeln($msg);

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
                    $this->getErrorOutput()->writeln("\033[31m$msg\033[0m");
                }
            }
        }

        if ($jsonOutput) {
            $this->getOutput()->writeln(
                json_encode($outputBuffer, JSON_UNESCAPED_SLASHES)
            );
        }

        if ($errorCount !== 0 || $violationCount !== 0) {
            return 1;
        }

        return 0;
    }

    private function _run($rulePath, $phpPath)
    {
        $phpParser = new PhpParser();
        $configParser = new ConfigParser();

        $rules = $configParser->parse($rulePath);
        foreach ($phpParser->parseFilesInDirectory($phpPath) as $phpFile) {
            foreach ($rules as $rule) {
                foreach ($rule->pattern->visit($phpFile->ast) as $match) {
                    yield new Match($phpFile->path, $match, $rule);
                }
            }
        }
    }
}
