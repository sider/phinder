<?php

function f($options)
{
}

f(['trivial' => 1]);

f(['trivial' => 1, 'important' => 2]);

f(['important' => 2, 'trivial' => 1]);
