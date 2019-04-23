<?php

namespace Phinder\Error;

class InvalidRule extends \RuntimeException
{
    public $key;

    public $index;

    public $path;

    public function __construct($key)
    {
        $this->key = $key;
    }
}
