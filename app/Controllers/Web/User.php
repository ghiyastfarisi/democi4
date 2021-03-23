<?php namespace App\Controllers\Web;

use App\Controllers\BaseController;

class User extends BaseController
{
	public function __construct() {}

	public function index()
	{
		$data = array(
			'_PageTitle' 	=> 'User Management',
			'_Pages' 		=> 'user/index',
			'_LoadJS'		=> array('@/userIndex.js')
		);
		return RenderTemplate($data);
	}
}
