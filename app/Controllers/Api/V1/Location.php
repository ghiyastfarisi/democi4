<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\LocationModel;

class Location extends BaseController
{
	protected $LocationModel;
	protected $validation;
	protected $db;

	function __construct()
	{
		$this->LocationModel = new LocationModel();
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

		$getType = isset($q['getType']) ? $q['getType'] : '';
		$getParent = isset($q['getParent']) && filter_var($q['getParent'], FILTER_VALIDATE_INT) ? (int)$q['getParent'] : 0;
		$transformLabel = isset($q['transformLabel']) && filter_var($q['transformLabel'], FILTER_VALIDATE_BOOLEAN) ? true : false;

		$resp = $this->LocationModel->GetAll($getType, $getParent, $transformLabel);

		$transformed = array(
			'data' => $resp
		);

		return ResponseOK($transformed);
	}
}
