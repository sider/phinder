<?php

namespace Phinder\Cli\Command;

use Phinder\Config\Parser as ConfigParser;
use Phinder\Php\Parser as PhpParser;
use Phinder\Cli\Command;

class TestCommand extends Command
{
    const ECODE_SUCCESS = 0;

    const ECODE_FAILURE = 2;

    protected function configure()
    {
        $this
            ->setName('test')
            ->setDescription('Test pattern(s)')
            ->addOption(...self::$configOptDef)
            ->addOption(...self::$formatOptDef);
    }

    protected function main()
    {
        $errorCount = 0;

        $config = $this->getConfig();

        $phpParser = new PhpParser();

        $configParser = new ConfigParser();

        foreach ($configParser->parseFilesInDirectory($config) as $r) {
            foreach ($r->failPatterns as $p) {
                $phpAst = $phpParser->parseString("<?php $p;");
                if (count($r->pattern->visit($phpAst)) === 0) {
                    $msg = "`$p` does not match the rule {$r->id}";
                    $msg .= ' but should match that rule.';
                    $this->getErrorOutput()->writeln($msg);
                    ++$errorCount;
                }
            }

            foreach ($r->passPatterns as $p) {
                $phpAst = $phpParser->parseString("<?php $p;");
                if (count($r->pattern->visit($phpAst)) !== 0) {
                    $msg = "`$p` matches the rule {$r->id}";
                    $msg .= ' but should not match that rule.';
                    $this->getErrorOutput()->writeln($msg);
                    ++$errorCount;
                }
            }
        }

        if ($errorCount === 0) {
            return self::ECODE_SUCCESS;
        } else {
            return self::ECODE_FAILURE;
        }
    }
}
