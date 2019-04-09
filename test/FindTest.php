<?php


class FindTest extends CliTest
{
    protected static $commandName = 'find';

    /**
     * @dataProvider useCaseProvider
     */
    public function testUseCase($dir)
    {
        $config = $dir.'/phinder.yml';
        $this->exec(['--config' => $config, '--format' => 'json', 'path' => $dir]);
        $this->assertSame($this->getStatusCode(), 1);
    }

    public function useCaseProvider()
    {
        $useCases = [];
        foreach (glob('test/res/*') as $file) {
            if (is_dir($file)) {
                $useCases[] = [$file];
            }
        }

        return $useCases;
    }
}
