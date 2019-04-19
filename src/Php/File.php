<?php

namespace Phinder\Php;

final class File
{
    public $path;

    public $ast;

    public function __construct($path, $ast)
    {
        $this->path = $path;
        $this->ast = $ast;
    }
}
