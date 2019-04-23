<?php

namespace Phinder\Config;

final class Rule
{
    public $id;

    public $pattern;

    public $message;

    public $justifications;

    public $failPatterns;

    public $passPatterns;

    public function __construct(
        $id,
        $pattern,
        $message,
        $justifications,
        $passPatterns,
        $failPatterns
    ) {
        $this->id = $id;
        $this->pattern = $pattern;
        $this->message = $message;
        $this->justifications = $justifications;
        $this->passPatterns = $passPatterns;
        $this->failPatterns = $failPatterns;
    }
}
