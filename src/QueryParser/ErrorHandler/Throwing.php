<?php declare(strict_types=1);

namespace Phinder\QueryParser\ErrorHandler;

use Phinder\QueryParser\Error;
use Phinder\QueryParser\ErrorHandler;

/**
 * Error handler that handles all errors by throwing them.
 *
 * This is the default strategy used by all components.
 */
class Throwing implements ErrorHandler
{
    public function handleError(Error $error) {
        throw $error;
    }
}
