<?php

use Swaggest\JsonDiff\JsonDiff;

class FindTest extends CliTest
{
    protected static $commandName = 'find';

    /**
     * @expectedException \Phinder\Error\InvalidRule
     */
    public function testInvalidId()
    {
        $this->_execInvalidConfig('invalid-id');
    }

    /**
     * @expectedException \Phinder\Error\InvalidRule
     */
    public function testInvalidJustification()
    {
        $this->_execInvalidConfig('invalid-justification');
    }

    /**
     * @expectedException \Phinder\Error\InvalidRule
     */
    public function testInvalidMessage()
    {
        $this->_execInvalidConfig('invalid-message');
    }

    /**
     * @expectedException \Phinder\Error\InvalidPattern
     */
    public function testInvalidPattern()
    {
        $this->_execInvalidConfig('invalid-pattern');
    }

    /**
     * @expectedException \Phinder\Error\InvalidYaml
     */
    public function testInvalidRule()
    {
        $this->_execInvalidConfig('invalid-rule');
    }

    /**
     * @expectedException \Phinder\Error\InvalidRule
     */
    public function testInvalidTestFail()
    {
        $this->_execInvalidConfig('invalid-test-fail');
    }

    /**
     * @expectedException \Phinder\Error\InvalidRule
     */
    public function testInvalidTestPass()
    {
        $this->_execInvalidConfig('invalid-test-pass');
    }

    /**
     * @expectedException \Phinder\Error\InvalidYaml
     */
    public function testInvalid()
    {
        $this->_execInvalidConfig('invalid-yaml');
    }

    /**
     * @expectedException \Phinder\Error\InvalidRule
     */
    public function testNoId()
    {
        $this->_execInvalidConfig('no-id');
    }

    /**
     * @expectedException \Phinder\Error\InvalidRule
     */
    public function testNoMessage()
    {
        $this->_execInvalidConfig('no-message');
    }

    /**
     * @expectedException \Phinder\Error\InvalidRule
     */
    public function testNoPattern()
    {
        $this->_execInvalidConfig('no-pattern');
    }

    /**
     * @expectedException \Phinder\Error\FileNotFound
     */
    public function testNonExistent()
    {
        $this->_execInvalidConfig('non-existent');
    }

    /**
     * @
     */

    /**
     * @expectedException \Phinder\Error\InvalidPHP
     */
    public function testInvalidPHP()
    {
        $this->exec(
            [
                '--config' => 'sample',
                'path' => 'test/res/invalid.badphp',
            ]
        );
    }

    /**
     * @dataProvider useCaseProvider
     */
    public function testUseCase($dir)
    {
        $this->exec(
            [
                '--config' => $dir,
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
        foreach (glob('test/res/usecase/*') as $file) {
            if (is_dir($file)) {
                $useCases[] = [$file];
            }
        }

        return $useCases;
    }

    private function _execInvalidConfig($name)
    {
        $this->exec(['--config' => "test/res/invalid-config/$name.yml"]);
    }
}
