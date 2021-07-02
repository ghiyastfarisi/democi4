<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\UpiSarprasModel;
use App\Models\SarprasModel;

class UpiSarpras extends BaseController
{
	protected $UpiSarprasModel;
	protected $validation;
	protected $db;

	function __construct()
	{
		$this->UpiSarprasModel = new UpiSarprasModel();
		$this->validation = \Config\Services::validation();
		$this->db = db_connect();
	}

	public function GetByUpi($id = 0)
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'GET') {
			return ResponseNotAllowed();
		}

		$q = $req->getGet();

		$getName = isset($q['getName']) && filter_var($q['getName'], FILTER_VALIDATE_BOOLEAN) ? true : false;

		$this->UpiSarprasModel->join('tbl_sarpras', 'tbl_sarpras.id = tbl_upi_sarpras.sarpras_id AND tbl_upi_sarpras.upi_id = '.$id, 'right');

		$resp = $this->UpiSarprasModel->findAll();

		$transformed = array(
			'data' => $resp
		);

		return ResponseOK($transformed);
	}
}
