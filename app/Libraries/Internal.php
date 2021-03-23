<?php namespace App\Libraries;

use Config\App;

class Internal {
    public function GetTemplateStyle()
    {
        $app = config(App::class);
        return $app->TemplateStyle;
    }
}