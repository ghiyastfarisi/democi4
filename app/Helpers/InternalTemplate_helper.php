<?php

use Config\App;

function GetTemplateStyle()
{
    return config(App::class)->TemplateStyle;
}
