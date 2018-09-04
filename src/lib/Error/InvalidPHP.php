<?php

namespace Phinder\Error;

use Phinder\Error;


class InvalidPHP extends Error {

    public $path;

    public $error;

    public function __construct($path, $error) {
        $this->path = $path;
        $this->error = $error;
    }

}
