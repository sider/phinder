<?php

namespace Phinder\PatternParser\Node;

abstract class AbstractNode
{
    abstract public function match($phpNode);
}
