<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

class MethodInvocation extends Node
{
    private $_receiver;

    private $_invocation;

    public function __construct($receiver, $invocation)
    {
        $this->_receiver = $receiver;
        $this->_invocation = $invocation;
    }

    public function match($phpNode)
    {
        return true;
    }

    public function getChildrenArray()
    {
        return [
            $this->_receiver->toArray(),
            $this->_invocation->toArray(),
        ];
    }
}
