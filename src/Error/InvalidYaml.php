<?php

namespace Phinder\Error;

class InvalidYaml extends \RuntimeException
{

    public $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

}
