<?php

namespace Phinder\Error;

class FileNotFound extends \RuntimeException
{
    public $path;

    public function __construct($path)
    {
        $this->path = $path;
    }
}
