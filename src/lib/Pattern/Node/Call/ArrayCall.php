<?php

namespace Phinder\Pattern\Node\Call;

use Phinder\Pattern\Node\Call;

final class ArrayCall extends Call
{
    public function __construct($arguments)
    {
        parent::__construct($arguments);
    }

    protected function matchNode($phpNode)
    {
        return $this->matchArguments($phpNode->items);
    }

    protected function getSubNodeNames()
    {
        return ['arguments'];
    }

    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_Array';
    }
}
