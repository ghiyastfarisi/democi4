<?php

namespace App\Controllers;

class Auth extends BaseController
{
	public function login()
    {
        $this->SetSession(array(
            'login'     => true,
            'uid'       => 1,
            'username'  => 'userX',
            'role'      => 'admin'
        ));

        return redirect()->to('/web/dashboard');
    }

    public function logout()
    {
        $this->DestroySession();

        return redirect()->to('/web/login');
    }
}
