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

    protected function matchPhpNode($phpNode)
    {
        return true;
    }

    protected function getChildrenArray()
    {
        return [
            $this->_receiver->toArray(),
            $this->_invocation->toArray(),
        ];
    }
}
