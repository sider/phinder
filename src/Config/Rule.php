<?php

namespace Phinder\Config;

final class Rule
{
    public $id;

    public $pattern;

    public $message;

    public $justifications;

    public $fail_patterns;

    public $pass_patterns;

    public function __construct(
        $id,
        $pattern,
        $message,
        $justifications,
        $pass_patterns,
        $fail_patterns
    ) {
        $this->id = $id;
        $this->pattern = $pattern;
        $this->message = $message;
        $this->justifications = $justifications;
        $this->pass_patterns = $pass_patterns;
        $this->fail_patterns = $fail_patterns;
    }
}
