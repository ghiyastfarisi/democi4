<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CheckLogin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (null==session('auth'))
        {
            // if page is login or register redirect to dashboard
            if (!url_is('web/login'))
            {
                return redirect()->to('/web/login');
            }
        }

        if (null!=session('auth')){
            // if page is login or register redirect to dashboard
            if (url_is('web/login') || url_is('web') || url_is('/'))
            {
                return redirect()->to('/web/dashboard');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {}
}
