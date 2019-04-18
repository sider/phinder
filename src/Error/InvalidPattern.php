<?php

namespace Phinder\Error;

class InvalidPattern extends \RuntimeException
{
    public $pattern;

    public $id;

    public $path;

    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }
}
