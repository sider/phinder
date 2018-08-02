<?php

namespace Phinder;


final class Match {

    public $path;

    public $xml;

    public $rule;

    public function __construct($path, $xml, $rule) {
        $this->path = $path;
        $this->xml = $xml;
        $this->rule = $rule;
    }

}
