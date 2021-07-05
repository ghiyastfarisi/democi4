<?php

use Config\App;

function GetTemplateStyle()
{
    return config(App::class)->TemplateStyle;
}

function ViewPath($extendPath = null)
{
    $viewPath = 'templates/'.GetTemplateStyle();

    return (null !== $extendPath) ? $viewPath.'/'.$extendPath : $viewPath.'/index';
}

function _globalCSS()
{
    return array(
        '@/core.css'
    );
}

function _globalJS()
{
    return array(
        '@/core.js'
    );
}

function ReplaceAlias($path)
{
    $hasAlias = strpos($path, '@/');

    if ($hasAlias == 0) {
        $path = str_replace('@/', 'dist/', $path);
    }

    return $path;
}

function RenderTemplate($data = null)
{
    if (isset($data['_Pages']) && $data['_Pages'] != '') {
        $data['_Pages'] = 'pages/'.$data['_Pages'];
    }

    // default inject mandatory css
    $injectCSS = array();
    if (isset($data['_LoadCSS'])) {
        if (is_array($data['_LoadCSS'])) {
            $injectCSS = $data['_LoadCSS'];
        }
    }
    $data['_LoadCSS'] = array_merge(_globalCSS(), $injectCSS);

    // default inject mandatory css
    $injectJS = array();
    if (isset($data['_LoadJS'])) {
        if (is_array($data['_LoadJS'])) {
            $injectJS = $data['_LoadJS'];
        }
    }
    $data['_LoadJS'] = array_merge(_globalJS(), $injectJS);

    // default inject menu
    $listOfMenu = array(
        'dashboard' =>  array(
            'slug'  => 'dashboard',
            'title' => 'Dashboard',
            'icon'  => 'fas fa-home',
            'url'   => base_url('web/dashboard')
        ),
        'user' => array(
            'slug'  => 'user',
            'title' => 'User',
            'icon'  => 'fas fa-user',
            'url'   => base_url('web/user')
        ),
        'pembinamutu' => array(
            'slug'  => 'pembina-mutu',
            'title' => 'Pembina mutu',
            'icon'  => 'fas fa-user-secret',
            'url'   => base_url('web/pembina-mutu')
        ),
        'upi' => array(
            'slug'  => 'upi',
            'title' => 'UPI',
            'icon'  => 'fas fa-building',
            'url'   => base_url('web/upi')
        ),
        'laporan' => array(
            'slug'  => 'laporan',
            'title' => 'Laporan',
            'icon'  => 'fas fa-book',
            'url'   => base_url('web/laporan')
        ),
        'setting' => array(
            'slug'  => 'setting',
            'title' => 'Setting',
            'icon'  => 'fas fa-tools',
            'url'   => base_url('web/user/setting')
        ),
        'logout' => array(
            'slug'  => 'logout',
            'title' => 'Logout',
            'icon'  => 'fas fa-sign-out-alt',
            'url'   => base_url('logout')
        )
    );

    $userMenu = ['pembina_mutu' => [ 'dashboard', 'pembinamutu', 'upi', 'laporan', 'setting', 'logout' ] ];
    $menuData = $listOfMenu;
    $userSession = session('auth');

    if (isset($userSession) && isset($userSession['role'])) {
        $role = $userSession['role'];
        if (isset($userMenu[$role])) {
            $menuData = array();
            foreach($userMenu[$role] as $k => $v) {
                array_push($menuData, $listOfMenu[$v]);
            }
        }
    }

    $data['_Menu'] = $menuData;

    $vp =  ViewPath();

    if (isset($data['_ExtendPath']))
    {
        $vp = ViewPath($data['_ExtendPath']);
    }


    return view($vp, (null !== $data) ? $data : array());
}

function PrintArgs($args = array(), $type = "") {
    if (isset($args[$type])) {
        return $args[$type];
    } else {
        if (isset($_SERVER['CI_ENVIRONMENT']) && $_SERVER['CI_ENVIRONMENT'] === 'production') {
            return '';
        } else {
            return '<strong><i><span style="color:red">`'.$type.'` not found</span></i></strong>';
        }
    }
}

function ToTittleCase($words) {
    $arr = array();
    $pattern = '/([;:,-.\/ X])/';
    $array = preg_split($pattern, $words, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

    foreach($array as $k => $v)
        $result .= ucwords(strtolower($v));

    return $result;
}