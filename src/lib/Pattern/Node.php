<?php

namespace Phinder\Pattern;

abstract class Node
{
    abstract public function match($phpNode);
}
