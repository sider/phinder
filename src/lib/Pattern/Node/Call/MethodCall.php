<?php

namespace Phinder\Pattern\Node\Call;

use Phinder\Pattern\Node\Call;

final class MethodCall extends Call
{
    protected $receiver;

    protected $identifier;

    public function __construct($receiver, $identifier, $arguments)
    {
        parent::__construct($arguments);
        $this->receiver = $receiver;
        $this->identifier = $identifier;
    }

    protected function matchNode($phpNode)
    {
        return $this->receiver->match($phpNode->var)
            && $this->identifier->match($phpNode->name)
            && $this->matchArguments($phpNode->args);
    }

    protected function getSubNodeNames()
    {
        return ['receiver', 'identifier', 'arguments'];
    }

    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_MethodCall';
    }
}
