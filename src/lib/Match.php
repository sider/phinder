<?php

namespace Phinder;


final class Match {

    public $path;

    public $line;

    public $rule;

    public function __construct($path, $line, $rule) {
        $this->path = $path;
        $this->line = $line;
        $this->rule = $rule;
    }

}
