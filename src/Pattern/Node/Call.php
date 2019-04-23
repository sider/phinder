<?php

namespace Phinder\Pattern\Node;

use Phinder\Pattern\Node;

abstract class Call extends Node
{
    protected $arguments;

    public function __construct($arguments)
    {
        $this->arguments = $arguments;
    }

    protected static function matchArguments($patternNodes, $phpNodes)
    {
        $pattern0 = $patternNodes[0] ?? null;
        $php0 = $phpNodes[0] ?? null;

        if ($pattern0 === null) {
            if ($php0 === null) {
                return true;
            } else {
                return false;
            }
        } else {
            if ($php0 === null) {
                if ($pattern0 === Node::ELLIPSIS) {
                    $pattern1 = $patternNodes[1] ?? null;
                    if ($pattern1 === null) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                if ($pattern0 === Node::ELLIPSIS) {
                    $pattern1 = $patternNodes[1] ?? null;
                    if ($pattern1 === null) {
                        return true;
                    } else {
                        if ($pattern1->match($php0)) {
                            return static::matchArguments(
                                array_slice($patternNodes, 2),
                                array_slice($phpNodes, 1)
                            );
                        } else {
                            return static::matchArguments(
                                $patternNodes,
                                array_slice($phpNodes, 1)
                            );
                        }
                    }
                } else {
                    return $pattern0->match($php0) && static::matchArguments(
                        array_slice($patternNodes, 1),
                        array_slice($phpNodes, 1)
                    );
                }
            }
        }
    }
}
