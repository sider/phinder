<?php

function myfunc($arg) {
    // Do something in a naive way.
}

function myfunc_100_times_faster($arg, $flag = null) {
    // Do something in a newly developed way.
}

myfunc($obj);

myfunc_100_times_faster($obj);
