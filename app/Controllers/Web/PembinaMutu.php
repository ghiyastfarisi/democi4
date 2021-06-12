<?php namespace App\Controllers\Web;

use App\Controllers\BaseController;

class PembinaMutu extends BaseController
{
	public function __construct() {}

	public function index($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'Pembina Mutu',
			'_PageSectionSubTitle' 	=> ''
		);

		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Pembina Mutu',
			'_Pages' 		=> 'pembina_mutu/index',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/pembinamutuindex.js'),
			'_ActiveSlug'	=> 'pembina-mutu'
		);

		return RenderTemplate($data);
	}

	public function get($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'Pembina Mutu',
			'_PageSectionSubTitle' 	=> ''
		);
		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Pembina Mutu Detail Page',
			'_Pages' 		=> 'pembina_mutu/get_id',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/pembinamutugetid.js'),
			'_ActiveSlug'	=> 'pembina-mutu'
		);
		return RenderTemplate($data);
	}
}
