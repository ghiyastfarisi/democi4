<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function logout()
    {
        setcookie('auth', time()-3600);
        $this->DestroySession();

        return redirect()->to('/web/login');
    }
}
