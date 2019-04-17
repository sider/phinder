<?php

namespace Phinder\Pattern\Node\Call;

use Phinder\Pattern\Node\Call;

final class FunctionCall extends Call
{
    protected $identifier;

    public function __construct($identifier, $arguments)
    {
        parent::__construct($arguments);
        $this->identifier = $identifier;
    }

    protected function matchNode($phpNode)
    {
        return $this->identifier->match($phpNode->name)
            && $this->matchArguments($phpNode->args);
    }

    protected function getSubNodeNames()
    {
        return ['identifier', 'arguments'];
    }

    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_FuncCall';
    }
}
