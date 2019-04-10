<?php

class C
{
    public function m(...$args)
    {
    }
}

(new C())->m();
(new C())->m(1);
