<?php

namespace Phinder\Pattern\Node\Call;

use Phinder\Pattern\Node\Call;
use Phinder\Pattern\Node\ArrayArgument;

final class ArrayCall extends Call
{
    public function __construct($arguments)
    {
        parent::__construct($arguments);
    }

    protected function matchNode($phpNode)
    {
        $arguments = [];
        $negations = [];

        foreach ($this->arguments as $a) {
            if ($a instanceof ArrayArgument) {
                if ($a->negation) {
                    $negations[] = $a;
                } else {
                    $arguments[] = $a;
                }
            } else {
                $arguments[] = $a;
            }
        }

        foreach ($negations as $arg) {
            if (self::_argumentExists($arg, $phpNode->items)) {
                return false;
            }
        }

        return $this->matchArguments($arguments, $phpNode->items);
    }

    protected function getSubNodeNames()
    {
        return ['arguments'];
    }

    protected function isTargetType($phpNodeType)
    {
        return $phpNodeType === 'Expr_Array';
    }

    private static function _argumentExists($arg, $items)
    {
        foreach ($items as $item) {
            if ($arg->match($item)) {
                return true;
            }
        }

        return false;
    }
}
