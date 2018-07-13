<?php declare(strict_types=1);

namespace Phinder\QueryParser;

interface Builder
{
    /**
     * Returns the built node.
     *
     * @return Node The built node
     */
    public function getNode() : Node;
}
