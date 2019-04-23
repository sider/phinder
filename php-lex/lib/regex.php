<?php

require_once __DIR__.'/escape.php';

function buildRegex($tokens)
{
    $regs = ['\t+|\s+'];
    foreach ($tokens as $token) {
        $n = $token['name'];
        $r = escape($token['expr'], '"');
        $r = escape($r, '/');
        $r = trim($r);
        $regs[] = "(?<$n>$r)";
    }

    $regex = implode('|', $regs);

    return "^($regex)";
}
