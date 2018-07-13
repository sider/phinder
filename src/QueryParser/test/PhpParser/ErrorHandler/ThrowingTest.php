<?php declare(strict_types=1);

namespace QueryParser\ErrorHandler;

use QueryParser\Error;
use PHPUnit\Framework\TestCase;

class ThrowingTest extends TestCase
{
    /**
     * @expectedException \QueryParser\Error
     * @expectedExceptionMessage Test
     */
    public function testHandleError() {
        $errorHandler = new Throwing();
        $errorHandler->handleError(new Error('Test'));
    }
}
