<?php

namespace Phinder;

final class Match
{
    public $path;

    public $phpNode;

    public $rule;

    public function __construct($path, $phpNode, $rule)
    {
        $this->path = $path;
        $this->phpNode = $phpNode;
        $this->rule = $rule;
    }
}
