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
        'https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css'
    );
}

function _globalJS()
{
    return array(
        'https://cdn.jsdelivr.net/npm/vue@2.6.12'
    );
}

function ReplaceAlias($path)
{
    $hasAlias = strpos($path, '@/');

    if ($hasAlias == 0) {
        $path = str_replace('@/', 'dist/js/', $path);
    }

    return $path;
}

function RenderTemplate($data = null)
{
    if (isset($data['_Pages'])) {
        $data['_Pages'] = 'pages/'.$data['_Pages'];
    }

    // default inject mandatory js and css
    $injectCSS = array();
    if (isset($data['_LoadCSS'])) {
        if (is_array($data['_LoadCSS'])) {
            $injectCSS = $data['_LoadCSS'];
        }
    }

    $injectJS = array();
    if (isset($data['_LoadJS'])) {
        if (is_array($data['_LoadJS'])) {
            $injectJS = $data['_LoadJS'];
        }
    }

    $data['_LoadCSS'] = array_merge(_globalCSS(), $injectCSS);
    $data['_LoadJS'] = array_merge(_globalJS(), $injectJS);

    return view(ViewPath(), (null !== $data) ? $data : array());
}