<?php

namespace Phinder;

final class PhpFile
{
    public $path;

    public $ast;

    public function __construct($path, $ast)
    {
        $this->path = $path;
        $this->ast = $ast;
    }
}
