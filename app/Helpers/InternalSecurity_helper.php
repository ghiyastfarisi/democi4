<?php

function SecureDecorator($p)
{
    $a = hash('snefru', base64_encode($p));
    $b = hash('ripemd320', base64_encode($p.$a));
    $c = hash('whirlpool', base64_encode($p.$b));
    $p = hash('sha512', base64_encode($p.$c));
    return $p.base64_encode($p);
}

function SendMail($recipient='',$subject='',$body='',$mailTemplate='')
{
    $email = \Config\Services::email();

    $email->setTo($recipient);
    $email->setSubject($subject);
    $email->setMessage($body);

    return $email->send();
}

function ValidateRole($allowed = array())
{
    $match = array();
    $auth = session('auth');
    $role = $auth['role'];
    $found = false;

    foreach($allowed as $v) {
        if ($v === $role) {
            $found = true;
        }
    }

    if (!$found) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    return false;
}