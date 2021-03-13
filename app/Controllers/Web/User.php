<?php namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Controllers\Api\V1\User as UserApi;

class User extends BaseController
{
	public function index()
	{
		return view('user');
	}

	public function GetUserApi($id = 0) {
		$user_api = new UserApi();
		$getUser = $user_api->getUser($id);

		return 'Call GetUserApi() then '.$getUser;
	}
}
