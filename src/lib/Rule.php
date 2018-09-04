<?php

namespace Phinder;

use Phinder\Error\InvalidRule;


final class Rule {

    public $id;

    public $xpath;

    public $message;

    public $justifications;

    public $befores;

    public $afters;

    public function __construct($id, $xpath, $message, $justifications, $befores, $afters) {
        $this->id = $id;
        $this->xpath = $xpath;
        $this->message = $message;
        $this->justifications = $justifications;
        $this->befores = $befores;
        $this->afters = $afters;
    }

}
