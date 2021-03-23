<?php namespace App\Controllers\Web;

use App\Controllers\BaseController;

class User extends BaseController
{
	public function __construct() {}

	public function index()
	{
		return view('templates/'.GetTemplateStyle().'/index');
	}
}
