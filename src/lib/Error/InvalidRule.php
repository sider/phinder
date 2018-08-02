<?php

namespace Phinder\Error;

use Phinder\Error;


class InvalidRule extends Error {

    public $key;

    public $index;

    public $path;

    public function __construct($key) {
        $this->key = $key;
    }

}
