<?php

namespace Phinder;

final class Wildcard extends \PhpParser\Node\Expr
{
    /**
     * @codeCoverageIgnore
     */
    public function getSubNodeNames(): array
    {
        throw new Exception();
    }

    /**
     * @codeCoverageIgnore
     */
    public function getType(): string
    {
        throw new Exception();
    }
}
