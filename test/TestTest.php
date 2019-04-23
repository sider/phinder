<?php

class TestTest extends CliTest
{
    protected static $commandName = 'test';

    public function testSuccess()
    {
        $this->exec(['--config' => 'test/res/test-success.yml']);
        $this->assertSame($this->getStatusCode(), 0);
    }

    public function testFail()
    {
        $this->exec(['--config' => 'test/res/test-fail.yml']);
        $this->assertSame($this->getStatusCode(), 1);
    }
}
