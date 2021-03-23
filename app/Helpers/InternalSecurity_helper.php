<?php

function SecureDecorator($p)
{
    $a = hash('snefru', base64_encode($p));
    $b = hash('ripemd320', base64_encode($p.$a));
    $c = hash('whirlpool', base64_encode($p.$b));
    $p = hash('sha512', base64_encode($p.$c));
    return $p.base64_encode($p);
}