<?php namespace App\Controllers\Web;

use App\Controllers\BaseController;

class Laporan extends BaseController
{
	public function __construct() {}

	public function index($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'Laporan',
			'_PageSectionSubTitle' 	=> 'Laporan Pembina Mutu'
		);

		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Laporan',
			'_Pages' 		=> 'laporan/index',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/laporanindex.js'),
			'_ActiveSlug'	=> 'laporan'
		);

		return RenderTemplate($data);
	}

	public function get($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'Laporan Detail',
			'_PageSectionSubTitle' 	=> 'Laporan Kegiatan Pembina Mutu'
		);

		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Laporan Detail',
			'_Pages' 		=> 'laporan/detail',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/laporandetail.js'),
			'_ActiveSlug'	=> 'laporan'
		);

		return RenderTemplate($data);
	}

	public function create($id = 0)
	{
		$args = array(
			'_PageSectionTitle' 	=> 'Laporan',
			'_PageSectionSubTitle' 	=> 'Buat Laporan Baru'
		);

		$data = array(
			'args' 			=> $args,
			'_PageTitle' 	=> 'Laporan',
			'_Pages' 		=> 'laporan/create',
			'_LoadCSS'		=> array(),
			'_LoadJS'		=> array('@/laporancreate.js'),
			'_ActiveSlug'	=> 'laporan'
		);

		return RenderTemplate($data);
	}
}
