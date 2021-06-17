<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\BadgeModel;

class Badge extends BaseController
{
	protected $BadgeModel;
	protected $validation;
	protected $db;

	function __construct()
	{
		$this->BadgeModel = new BadgeModel();
	}

	public function Get()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'GET') {
			return ResponseNotAllowed();
		}

		$q = $req->getGet();
		$transformLabel = isset($q['transformLabel']) && filter_var($q['transformLabel'], FILTER_VALIDATE_BOOLEAN) ? true : false;
		$category = isset($q['category']) ? $q['category'] : '';
		$where = array();

		if ($category != '')
		{
			$where = array_merge($where, [ 'category' => $category ]);
		}

		$resp = $this->BadgeModel->where($where)->findAll();

		if ($transformLabel)
		{
			foreach($resp as $k => $v)
			{
				$resp[$k] = array(
					'id' 	=> $v['id'],
					'label'	=> $v['name']
				);
			}
		}

		$transformed = array(
			'data' => $resp
		);

		return ResponseOK($transformed);
	}
}
