<?php

namespace Phinder\Pattern\Node\Call;

use Phinder\Pattern\Node\Call;

final class StaticMethodCall extends Call
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
        return $this->receiver->match($phpNode->class)
            && $this->identifier->match($phpNode->name)
            && static::matchArguments($this->arguments, $phpNode->args);
    }

    protected function getSubNodeNames()
    {
        return ['receiver', 'identifier', 'arguments'];
    }

    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_StaticCall';
    }
}
