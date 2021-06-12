<?php namespace App\Controllers\Web;

use App\Controllers\BaseController;

class Login extends BaseController
{
	public function __construct() {}

	public function index($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'Login',
			'_PageSectionSubTitle' 	=> ''
		);

		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Dashboard',
			'_Pages' 		=> '',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/login.js'),
			'_ActiveSlug'	=> '',
			'_ExtendPath'	=> 'login'
		);

		return RenderTemplate($data);
	}
}
