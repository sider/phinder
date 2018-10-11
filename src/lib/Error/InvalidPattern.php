<?php

namespace Phinder\Error;

use Phinder\Error;


class InvalidPattern extends Error
{

    public $pattern;

    public $error;

    public $id;

    public $path;

    public function __construct($pattern, $error)
    {
        $this->pattern = $pattern;
        $this->error = $error;
    }

}
