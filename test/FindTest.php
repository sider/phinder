<?php

use Swaggest\JsonDiff\JsonDiff;

class FindTest extends CliTest
{
    protected static $commandName = 'find';

    /**
     * @dataProvider useCaseProvider
     */
    public function testUseCase($dir)
    {
        $this->exec(
            [
                '--config' => $dir.'/phinder.yml',
                '--format' => 'json',
                'path' => $dir,
            ]
        );

        $outputJson = $this->getDisplayJson();
        $this->assertTrue($outputJson !== null);
        $this->assertTrue(array_key_exists('result', $outputJson));

        $results = $outputJson['result'];
        $this->assertTrue(is_array($results));

        $resultCount = count($results);
        $this->assertSame($this->getStatusCode(), $resultCount === 0 ? 0 : 1);

        $expectedJsonPath = $dir.'/expected.json';
        $expectedJsonStr = @file_get_contents($dir.'/expected.json');
        $expectedJson = json_decode($expectedJsonStr, true);
        $this->assertTrue($expectedJson !== null);

        $jsonDiff = new JsonDiff($outputJson, $expectedJson);
        $this->assertSame($jsonDiff->getDiffCnt(), 0);
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
