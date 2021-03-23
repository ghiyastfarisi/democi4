# CodeIgniter 4 Framework

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](http://codeigniter.com).

This repository holds the distributable version of the framework,
including the user guide. It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [the announcement](http://forum.codeigniter.com/thread-62615.html) on the forums.

The user guide corresponding to this version of the framework can be found
[here](https://codeigniter4.github.io/userguide/).

## Template Structure

Rendering a template view is recommended in this way
```
$data = array(
    '_PageTitle' 	=> 'Page title',
    '_Pages' 		=> 'user/index',
    '_LoadJS'		=> array('@/mycustomjs.js'),
    '_LoadCSS'		=> array('@/mycustomcss.css')
);

return RenderTemplate($data);
```

RenderTemplate is a cool helper to automatically render templating style inside democi4.
Currently it accepts 4 paramters to load some pages.
_PageTitle used as page title content
_Pages used to point which main page of that controller will be loaded, it will automatically read view/templates/templatesName/pages/*
_LoadJS used to load extra js file from public
_LoadCSS used to load extra js file from public

use @ before filename of js or css as Path aliasing to public/dist/*

## How to ?

run ```make build-image``` and ```make run-dev``` then, voila...

