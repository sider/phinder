<?php

require_once __DIR__.'/escape.php';

function findTokens($grammar)
{
    $tokens = [];
    foreach (explode(PHP_EOL, $grammar) as $line) {
        if ((strpos($line, '%token') === 0)) {
            $body = substr($line, strlen('%token') + 1);
            $array = explode(' ', $body, 2);

            $name = $array[0];
            $expr = substr($array[1], 1, strlen($array[1]) - 2);

            $tokens[] = ['name' => $name, 'expr' => unescape($expr, "'")];
        }
    }

    return $tokens;
}
