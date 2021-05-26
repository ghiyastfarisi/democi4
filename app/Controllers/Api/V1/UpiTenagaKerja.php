<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\UpiTenagaKerjaModel;

class UpiTenagaKerja extends BaseController
{
	protected $UpiTenagaKerjaModel;
	protected $validation;
	protected $db;

	function __construct()
	{
		$this->UpiTenagaKerjaModel = new UpiTenagaKerjaModel();
		$this->validation = \Config\Services::validation();
		$this->db = db_connect();
	}

	public function GetByUpi($id = 0)
	{
		$req = $this->request;

		if (0 === $id) {
			return ResponseNotFound();
		}

		if ($req->getMethod(TRUE) !== 'GET') {
			return ResponseNotAllowed();
		}

		$resp = $this->UpiTenagaKerjaModel->where('upi_id', $id)->first();

		$transformed = array(
			'data' => $resp
		);

		return ResponseOK($transformed);
	}
}
