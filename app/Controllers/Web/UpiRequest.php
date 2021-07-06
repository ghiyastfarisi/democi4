<?php namespace App\Controllers\Web;

use App\Controllers\BaseController;

class UpiRequest extends BaseController
{
	public function __construct() {}

	public function index($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'Manage Request UPI',
			'_PageSectionSubTitle' 	=> 'Request UPI Management Page'
		);

		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Upi Request Management',
			'_Pages' 		=> 'upirequest/index',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/upirequestindex.js'),
			'_ActiveSlug'	=> 'upi'
		);

		return RenderTemplate($data);
	}

	public function get($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'UPI',
			'_PageSectionSubTitle' 	=> 'detail page'
		);
		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Upi Detail Page',
			'_Pages' 		=> 'upi/get_id',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/upigetid.js'),
			'_ActiveSlug'	=> 'upi'
		);
		return RenderTemplate($data);
	}
}
