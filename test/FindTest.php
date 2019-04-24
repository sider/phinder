<?php

use Swaggest\JsonDiff\JsonDiff;

class FindTest extends CliTest
{
    protected static $commandName = 'find';

    public function testInvalidId()
    {
        $this->_execInvalidConfig('invalid-id', 'InvalidRule');
    }

    public function testInvalidJustification()
    {
        $this->_execInvalidConfig('invalid-justification', 'InvalidRule');
    }

    public function testInvalidMessage()
    {
        $this->_execInvalidConfig('invalid-message', 'InvalidRule');
    }

    public function testInvalidPattern()
    {
        $this->_execInvalidConfig('invalid-pattern', 'InvalidPattern');
    }

    public function testInvalidRule()
    {
        $this->_execInvalidConfig('invalid-rule', 'InvalidYaml');
    }

    public function testInvalidTestFail()
    {
        $this->_execInvalidConfig('invalid-test-fail', 'InvalidRule');
    }

    public function testInvalidTestPass()
    {
        $this->_execInvalidConfig('invalid-test-pass', 'InvalidRule');
    }

    public function testInvalid()
    {
        $this->_execInvalidConfig('invalid-yaml', 'InvalidYaml');
    }

    public function testNoId()
    {
        $this->_execInvalidConfig('no-id', 'InvalidRule');
    }

    public function testNoMessage()
    {
        $this->_execInvalidConfig('no-message', 'InvalidRule');
    }

    public function testNoPattern()
    {
        $this->_execInvalidConfig('no-pattern', 'InvalidRule');
    }

    public function testNonExistent()
    {
        $this->_execInvalidConfig('non-existent', 'FileNotFound');
    }

    public function testInvalidPhp()
    {
        $this->_execInvalidConfig(
            '../../../sample/phinder',
            'InvalidPhp',
            'test/res/invalid.php_'
        );
    }

    /**
     * @dataProvider useCaseProvider
     */
    public function testUseCase($dir)
    {
        $this->exec(
            [
                '--config' => "$dir",
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
        $this->assertSame($this->getStatusCode(), $resultCount === 0 ? 0 : 2);

        $expectedJsonPath = $dir.'/expected.json';
        $expectedJsonStr = @file_get_contents($dir.'/expected.json');
        $expectedJson = json_decode($expectedJsonStr, true);
        $this->assertTrue($expectedJson !== null);

        $jsonDiff = new JsonDiff($outputJson, $expectedJson);

        if ($jsonDiff->getDiffCnt() !== 0) {
            foreach ($outputJson['result'] as $r) {
                var_dump($r);
            }
        }
        $this->assertSame($jsonDiff->getDiffCnt(), 0);
    }

    private function _execInvalidConfig($fileName, $errorName, $path = 'test/res/')
    {
        $this->exec(
            [
                '--config' => "test/res/invalid-config/$fileName.yml",
                '--format' => 'json',
                'path' => $path,
            ]
        );

        $outputJson = $this->getDisplayJson();
        $this->assertNotSame($outputJson, null);
        $this->assertTrue(array_key_exists('errors', $outputJson));

        $errors = $outputJson['errors'];
        $this->assertTrue(is_array($errors));
        $this->assertSame($this->getStatusCode(), 1);
        $this->assertSame($errors[0]['type'], $errorName);
    }

    public function useCaseProvider()
    {
        $useCases = [];
        foreach (glob('test/res/usecase/*') as $file) {
            if (is_dir($file)) {
                $useCases[] = [$file];
            }
        }

        return $useCases;
    }
}
