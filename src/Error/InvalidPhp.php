<?php

namespace Phinder\Error;

class InvalidPhp extends \RuntimeException
{
    public $path;

    public $error;

    public function __construct($path, $error)
    {
        $this->path = $path;
        $this->error = $error;
    }
}
