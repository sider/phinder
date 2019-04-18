<?php

namespace Phinder\Cli\Command;

use Phinder\Cli\API;
use Phinder\PhpParser;

use Phinder\Cli\Command;

class TestCommand extends Command
{
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

        $phpParser = new PHPParser();

        foreach (API::parseRule($config) as $r) {
            foreach ($r->fail_patterns as $p) {
                $xml = $phpParser->parseStr("<?php $p;");
                if (0 === count($xml->xpath($r->xpath))) {
                    $msg = "`$p` does not match the rule {$r->id}";
                    $msg .= ' but should match that rule.';
                    $this->getOutput()->writeln($msg);
                    ++$errorCount;
                }
            }

            foreach ($r->pass_patterns as $p) {
                $xml = $phpParser->parseStr("<?php $p;");
                if (0 < count($xml->xpath($r->xpath))) {
                    $msg = "`$p` matches the rule {$r->id}";
                    $msg .= ' but should not match that rule.';
                    $this->getOutput()->writeln($msg);
                    ++$errorCount;
                }
            }
        }

        if ($errorCount === 0) {
            $this->getErrorOutput()->writeln('No error');

            return 0;
        } else {
            return 1;
        }
    }
}
