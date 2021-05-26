<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\PerubahanUpiModel;
use App\Models\UpiModel;

class PerubahanUpi extends BaseController
{
	protected $PerubahanUpiModel;
	protected $validation;
	protected $db;

	function __construct()
	{
		$this->PerubahanUpiModel = new PerubahanUpiModel();
		$this->validation = \Config\Services::validation();
		$this->db = db_connect();
	}

	public function GetAll()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'GET') {
			return ResponseNotAllowed();
		}

		$q = $req->getGet();

		$page = (isset($q['page'])) ? $q['page'] : 1;
		$limit = (isset($q['limit'])) ? $q['limit'] : 1;
		$offset = ($page - 1) * $limit;

		// filter injection here
		$byUpiId = isset($q['upiId']) && filter_var($q['upiId'], FILTER_VALIDATE_INT) ? (int)$q['upiId'] : 0;
		$byPembinaMutuId = isset($q['pembinaMutuId']) && filter_var($q['pembinaMutuId'], FILTER_VALIDATE_INT) ? (int)$q['pembinaMutuId'] : 0;

		$whereQuery = array();

		if ($byUpiId > 0) {
			$whereQuery = array_merge($whereQuery, ['upi_id' => $byUpiId]);
		}

		if ($byPembinaMutuId > 0) {
			$whereQuery = array_merge($whereQuery, ['pembina_mutu_id' => $byPembinaMutuId]);
		}

		$resp = $this->PerubahanUpiModel->where($whereQuery)->orderBy('id', 'desc')->findAll($limit, $offset);

		$countQuery = $this->PerubahanUpiModel->selectCount('id')->find();
		$count = (int)$countQuery[0]['id'];

		$pageAvailable = ceil((int)$count/(int)$limit);

		$current = $page;
		$next = ($pageAvailable <= $current) ? 0 : $page + 1;
		$prev = ($page == 1) ? 0 : $page - 1;

		$lists = [];

		if (count($resp) > 0) {
			$lists = [ (int)$current ];

			if ($prev > 0) {
				$limitPrev = ($next == 0) ? $prev-1 : $prev;
				for ($i = $current - 1; $i >= $limitPrev; $i--) {
					if ($i >= 1) {
						array_unshift($lists, (int)$i);
					}
				}
			}

			if ($next > 0) {
				$limitNext = ($prev == 0) ? $next+1 : $next;
				for ($i = $current + 1; $i <= $limitNext; $i++) {
					if ($i <= $pageAvailable) {
						array_push($lists, (int)$i);
					}
				}
			}
		}


		$transformed = array(
			'queries' 		=> array(
				'limit'		=> (int)$limit,
				'page'		=> (int)$page
			),
			'data' 			=> $resp,
			'pagination'	=> array(
				'total' 	=> $count,
				'current'	=> (int)$current,
				'next'		=> $next,
				'prev'		=> $prev,
				'list'		=> $lists
			)
		);

		return ResponseOK($transformed);
	}

	public function GetById($id = null)
	{
		$req = $this->request;

		if (null === $id) {
			return ResponseNotFound();
		}

		if ($req->getMethod(TRUE) !== 'GET') {
			return ResponseNotAllowed();
		}

		$resp = $this->PerubahanUpiModel->find($id);

		$transformed = array(
			'data' => $resp
		);

		return ResponseOK($transformed);
	}
}
