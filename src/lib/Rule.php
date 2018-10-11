<?php

namespace Phinder;

use Phinder\Error\InvalidRule;


final class Rule
{

    public $id;

    public $xpath;

    public $message;

    public $justifications;

    public $fail_patterns;

    public $pass_patterns;

    public function __construct(
        $id,
        $xpath,
        $message,
        $justifications,
        $pass_patterns,
        $fail_patterns
    ) {
        $this->id = $id;
        $this->xpath = $xpath;
        $this->message = $message;
        $this->justifications = $justifications;
        $this->pass_patterns = $pass_patterns;
        $this->fail_patterns = $fail_patterns;
    }

}
