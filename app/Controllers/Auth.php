<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function logout()
    {
        setcookie('auth', null, -1, '/');
        $this->DestroySession();

        return redirect()->to('/web/login');
    }
}
