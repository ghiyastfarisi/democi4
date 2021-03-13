<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;

class User extends BaseController
{
	public function index()
	{
		return 'api user v1 here...';
	}

	public function get()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'GET') {
			return 'error';
		}

		return $this->response->setJSON($req->getGet());
	}

	public function create()
	{
		$req = $this->request;
		$uri = $req->uri;

		if ($req->getMethod(TRUE) !== 'POST') {
			return 'error';
		}

		$resp = $req->getJSON();

		if ($req->getHeaderLine('Content-Type') === 'application/x-www-form-urlencoded') {
			$resp = $req->getPost();
		}

		return $this->response->setJSON($resp);
	}

	public function validWith($param = '')
	{
		$req = $this->request;
		$uri = $req->uri;

		return $this->response->setJSON(
			array(
				"queries" => array($uri->getQuery()),
				"params" => array($param)
			)
		);
	}
}
