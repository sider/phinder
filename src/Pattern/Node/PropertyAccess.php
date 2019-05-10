<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

final class PropertyAccess extends Node
{
    protected $owner;

    protected $identifier;

    public function __construct($owner, $identifier)
    {
        $this->owner = $owner;
        $this->identifier = $identifier;
    }

    protected function matchNode($phpNode)
    {
        return $this->owner->match($phpNode->var)
            && $this->identifier->match($phpNode->name);
    }

    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_PropertyFetch';
    }

    protected function getSubNodeNames()
    {
        return ['owner', 'identifier'];
    }
}
