<?php

namespace Phinder;

use PhpParser\Node\Expr;

class ArrayItem extends Expr
{
    public $key;

    public $value;

    public $byRef;

    public $negation;

    public function __construct(
        Expr $value, Expr $key = null, bool $byRef = false, array $attributes = []
    ) {
        parent::__construct($attributes);
        $this->key = $key;
        $this->value = $value;
        $this->byRef = $byRef;
        $this->negation = false;
    }

    public function negate()
    {
        $this->negation = true;
    }

    public function getSubNodeNames(): array
    {
        return ['key', 'value', 'byRef'];
    }

    public function getType(): string
    {
        return 'Expr_ArrayItem';
    }
}
