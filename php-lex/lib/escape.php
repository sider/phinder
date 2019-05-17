<?php

function unescape($string, $escapedChar)
{
    $result = '';
    for ($i = 0; $i < strlen($string); ++$i) {
        $cur = $string[$i];
        if ($cur === '\\') {
            $next = $string[$i + 1];
            if ($next === $escapedChar || $next === '\\') {
                $result .= $next;
                ++$i;
            } else {
                $result .= '\\';
            }
        } else {
            $result .= $cur;
        }
    }

    return $result;
}

function escape($string, $escapingChar)
{
    $result = '';
    for ($i = 0; $i < strlen($string); ++$i) {
        $cur = $string[$i];
        if ($cur === $escapingChar) {
            $result .= "\\$cur";
        } else {
            $result .= $cur;
        }
    }

    return $result;
}
