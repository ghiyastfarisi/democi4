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
			'_LoadJS'		=> array('@/upiindex.js'),
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

	public function create()
	{
		$args = array(
			'_PageSectionTitle' 	=> 'UPI',
			'_PageSectionSubTitle' 	=> 'create page'
		);
		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Upi Create Page',
			'_Pages' 		=> 'upi/create',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/upicreate.js'),
			'_ActiveSlug'	=> 'upi'
		);
		return RenderTemplate($data);
	}

	public function edit($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'UPI',
			'_PageSectionSubTitle' 	=> 'edit page'
		);
		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Upi Edit Page',
			'_Pages' 		=> 'upi/get_id',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/upiedit.js'),
			'_ActiveSlug'	=> 'upi'
		);
		return RenderTemplate($data);
	}

	public function request($id = 0, $kunjungan = '', $kunjungan_id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'UPI',
			'_PageSectionSubTitle' 	=> 'request edit page'
		);
		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Upi Request Edit Page',
			'_Pages' 		=> 'upi/get_id',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/upirequest.js'),
			'_ActiveSlug'	=> 'upi'
		);
		return RenderTemplate($data);
	}
}
