<?php

namespace Phinder\PatternParser;

abstract class Node
{
    abstract public function match($phpNode);
}
