<?php

$arr = array(
    '123foo',
    'bar'
);

$needle = 123;

if (in_array($needle, $arr)) {
    var_dump($arr);
    echo "Found $needle in \$arr\n";
}

if (in_array(123, $arr)) {
    echo "Found $needle in \$arr\n";
}
