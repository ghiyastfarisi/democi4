<?php namespace App\Controllers\Web;

use App\Controllers\BaseController;

class UpiRequest extends BaseController
{
	public function __construct() {}

	public function index($id = 0)
	{
		ValidateRole(array('admin'));

		$args = array(
			'_PageSectionTitle' 	=> 'Manage Request UPI',
			'_PageSectionSubTitle' 	=> 'Request UPI Management Page'
		);

		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Upi Request Page',
			'_Pages' 		=> 'upirequest/index',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/upirequestindex.js'),
			'_ActiveSlug'	=> 'upirequest'
		);

		return RenderTemplate($data);
	}

	public function get($id = 0)
	{
		ValidateRole(array('admin'));

		$args = array(
			'_PageSectionTitle' 	=> 'Manage Request UPI',
			'_PageSectionSubTitle' 	=> 'Detail Request UPI Management Page'
		);
		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Upi Request Detail Page',
			'_Pages' 		=> 'upirequest/get_id',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/upirequestid.js'),
			'_ActiveSlug'	=> 'upirequest'
		);
		return RenderTemplate($data);
	}
}
