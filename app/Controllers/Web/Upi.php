<?php namespace App\Controllers\Web;

use App\Controllers\BaseController;

class Upi extends BaseController
{
	public function __construct() {}

	public function index($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'Manage UPI',
			'_PageSectionSubTitle' 	=> 'UPI Management Page'
		);

		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Upi Management',
			'_Pages' 		=> 'upi/index',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/userindex.js'),
			'_ActiveSlug'	=> 'upi'
		);

		return RenderTemplate($data);
	}

	public function get($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'User',
			'_PageSectionSubTitle' 	=> 'detail page',
			'user_id'				=> $id
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
