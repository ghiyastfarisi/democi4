<?php namespace App\Controllers\Web;

use App\Controllers\BaseController;

class Login extends BaseController
{
	public function __construct() {}

	public function index($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'Kuy Login',
			'_PageSectionSubTitle' 	=> ''
		);

		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Dashboard',
			'_Pages' 		=> 'dashboard/index',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/dashboard.js'),
			'_ActiveSlug'	=> 'dashboard'
		);


		echo RenderTemplate($data);
        echo '<a href="/login">LOGIN</a>';
	}
}
