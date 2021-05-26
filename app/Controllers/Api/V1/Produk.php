<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\ProdukModel;

class Produk extends BaseController
{
	protected $ProdukModel;
	protected $validation;
	protected $db;

	function __construct()
	{
		$this->ProdukModel = new ProdukModel();
		$this->validation = \Config\Services::validation();
		$this->db = db_connect();
	}

	public function Get()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'GET') {
			return ResponseNotAllowed();
		}

		$q = $req->getGet();
		$transformLabel = isset($q['transformLabel']) && filter_var($q['transformLabel'], FILTER_VALIDATE_BOOLEAN) ? true : false;

		$resp = $this->ProdukModel->GetAll($transformLabel);

		$transformed = array(
			'data' => $resp
		);

		return ResponseOK($transformed);
	}
}
