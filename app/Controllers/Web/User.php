<?php namespace App\Controllers\Web;

use App\Controllers\BaseController;

class User extends BaseController
{
	public function __construct() {}

	public function index($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'Manage User',
			'_PageSectionSubTitle' 	=> 'User Management Page'
		);

		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'User Management',
			'_Pages' 		=> 'user/index',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/userindex.js'),
			'_ActiveSlug'	=> 'user'
		);

		return RenderTemplate($data);
	}

	public function get($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'User',
			'_PageSectionSubTitle' 	=> 'detail page'
		);
		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'User Detail Page',
			'_Pages' 		=> 'user/get_id',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/usergetid.js'),
			'_ActiveSlug'	=> 'user'
		);
		return RenderTemplate($data);
	}
}
