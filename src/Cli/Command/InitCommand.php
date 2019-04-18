<?php

namespace Phinder\Cli\Command;

use Phinder\Cli\Command;

class InitCommand extends Command
{
    private static $_SAMPLE_CONFIG = __DIR__.'/../../../sample/phinder.yml';

    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Create sample phinder.yml')
            ->addOption(...self::$configOptDef);
    }

    protected function main()
    {
        $config = $this->getConfig();

        if (file_exists($config)) {
            $this->getErrorOutput()->writeln(
                "Cannot generate $config: file already exists"
            );

            return 1;
        }

        copy(self::$_SAMPLE_CONFIG, $config);

        $this->getOutput()->writeln("`$config` has been created successfully");

        return 0;
    }
}
