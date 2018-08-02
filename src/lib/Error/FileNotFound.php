<?php

namespace Phinder\Error;

use Phinder\Error;


class FileNotFound extends Error {

    public $path;

    public function __construct($path) {
        $this->path = $path;
    }

}
