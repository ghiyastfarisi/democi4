<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\CountryModel;

class Country extends BaseController
{
	protected $CountryModel;
	protected $validation;
	protected $db;

	function __construct()
	{
		$this->CountryModel = new CountryModel();
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

		$resp = $this->CountryModel->GetAll($transformLabel);

		$transformed = array(
			'data' => $resp
		);

		return ResponseOK($transformed);
	}
}
