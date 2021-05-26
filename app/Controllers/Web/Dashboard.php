<?php namespace App\Controllers\Web;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
	public function __construct() {}

	public function index($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'Dashboard',
			'_PageSectionSubTitle' 	=> 'UPI Datacenter'
		);

		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Dashboard',
			'_Pages' 		=> 'dashboard/index',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/dashboard.js'),
			'_ActiveSlug'	=> 'dashboard'
		);

		return RenderTemplate($data);
	}
}
